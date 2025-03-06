<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RequestOrder extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'request_orders';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->code = static::getCode();
        });
        static::deleting(function ($model) {
            $model->files->each->delete();
            $model->products()->delete();
            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
        });
    }

    protected static function getCode()
    {
        $type = 'PO-CL'; // Purchase Order Client
        $lastRecord = self::whereYear('date', '=', date('Y'))
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    public function products(){
        return $this->hasMany(RequestOrderProduct::class, 'request_order_id')
                    ->join('products', 'request_order_products.product_id', '=', 'products.id')
                    ->orderBy('products.name', 'asc')
                    ->select('request_order_products.*'); // Hindari konflik kolom
    }

    public function getRemaining(){
        $purchases = $this->purchaseOrders()->where('status', '!=', 0)->with('products')->whereHas('products')->get();

        // Proses semua kuantitas dalam satu langkah menggunakan koleksi Laravel
        $qtyProcess = collect($purchases)
            ->flatMap(fn($po) => $po->products())
            ->groupBy('product_id')
            ->map(fn($products) => $products->sum('qty'))
            ->toArray();

        $qtyRequest = collect($this->products)
            ->groupBy('product_id')
            ->map(fn($products) => $products->sum('qty'))
            ->toArray();

        $qtyRemaining = collect($qtyRequest)->mapWithKeys(function ($requestQty, $productId) use ($qtyProcess) {
            $processedQty = $qtyProcess[$productId] ?? 0;
            return [$productId => $requestQty - $processedQty];
        })->toArray();

        return $qtyRemaining;
    }

    public function getIsPartialAttribute(){
        $check = $this->invoices && ($this->invoices->count() > 1 || ($this->invoice && $this->invoice->purchaseOrder));
        return $check ? 2 : null;
    }

    public function getProcessingAnalytics()
    {
        $analytics = collect();

        foreach ($this->products as $requestProduct) {
            $productId = $requestProduct->product_id;
            $requestedQty = $requestProduct->qty;

            // Ambil semua PurchaseOrder terkait dengan RequestOrder ini, kecuali status 0 (pending)
            $processedPurchases = $this->purchaseOrders()
                ->where('status', '!=', '0')
                ->with('products')
                ->get();

            // Hitung jumlah yang telah diproses dan selesai berdasarkan PurchaseOrderProduct
            $processedQty = 0;
            $finishedQty = 0;

            foreach ($processedPurchases as $purchase) {
                foreach ($purchase->products as $purchaseProduct) {
                    if ($purchaseProduct->product_id === $productId) {
                        $processedQty += $purchaseProduct->qty;
                        if ($purchase->status == '2') { // Status 2 adalah selesai
                            $finishedQty += $purchaseProduct->qty;
                        }
                    }
                }
            }

            // Hitung remaining qty (sisa yang belum diproses)
            $remainingQty = max($requestedQty - $processedQty, 0);

            // Hitung persentase pemrosesan
            $percentage = $requestedQty > 0 ? round(($processedQty / $requestedQty) * 100, 2) : 0;

            // Simpan ke dalam collection dengan keyBy product_id
            $analytics->put($productId, [
                'product_id'     => $productId,
                'requested_qty'  => $requestedQty,
                'processed_qty'  => $processedQty,
                'finished_qty'   => $finishedQty,
                'remaining_qty'  => $remainingQty,
                'percentage'     => $percentage,
            ]);
        }

        return $analytics;
    }
    public function isStillRemain()
    {
        $analytics = $this->getProcessingAnalytics();

        foreach ($analytics as $data) {
            if ($data['remaining_qty'] > 0) {
                return true; // Masih ada sisa yang belum diproses
            }
        }

        return false; // Semua sudah diproses
    }

    public function isFinished()
    {
        $analytics = $this->getProcessingAnalytics();

        foreach ($analytics as $data) {
            if ($data['finished_qty'] < $data['requested_qty'] || $data['remaining_qty'] > 0) {
                return false;
            }
        }

        return true;
    }

    public static function totalProcessed()
    {
        $reqorders = self::whereHas('invoices')->where('status', '!=', '0')->get();
        $totalProcessed = 0;
        foreach ($reqorders as $reqorder) {
            $invoices = $reqorder->invoices;
            foreach ($invoices as $inv) {
                $totalProcessed += ($inv->sum_paid_total ?? 0);
            } 
        }   
        return round($totalProcessed);
    }

    public static function remainingProcessed(){
        $allTotal = self::all()->sum('total_price_taxed');
        return round($allTotal - self::totalProcessed());
    }

    public function invoice(){
        return $this->hasOne(RequestOrderInvoice::class, 'request_order_id');
    }

    public function files(){
        return $this->hasMany(RequestOrderFile::class, 'request_order_id')->orderBy('name', 'asc');
    }

    public function client(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function pic(){
        return $this->belongsTo(ClientPic::class, 'client_pic_id');
    }

    public function invoices(){
        return $this->hasMany(RequestOrderInvoice::class, 'request_order_id');
    }

    public function purchaseOrders(){
        return $this->hasMany(PurchaseOrder::class, 'request_order_id');
    }
}
