<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\Sale\SaleListRequest;
use App\Http\Requests\Api\V1\App\Sale\StoreSaleRequest;
use App\Http\Requests\Api\V1\App\Sale\StoreSaleReturnRequest;
use App\Http\Requests\Api\V1\App\Sale\SubmitPaymentRequest;
use App\Http\Requests\Api\V1\App\Sale\UpdateSaleRequest;
use App\Http\Resources\Api\V1\App\Sale\SaleResource;
use App\Http\Swagger\SaleApiDocInterface;
use App\Models\Sale;
use App\Services\InvoiceService;
use App\Services\SaleService;
use App\Services\SaleEditService;
use App\Services\SaleReturnService;
use App\Utils\UserType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class SaleController extends BaseApiController implements SaleApiDocInterface
{
    public function __construct(
        protected SaleService $saleService,
        protected SaleEditService $saleEditService,
        protected SaleReturnService $saleReturnService,
        protected InvoiceService $invoiceService
    ) {}

    public function index(SaleListRequest $request): JsonResponse
    {
        try {
            $userId = auth()->id();
            if (auth()->user()->user_type == UserType::SR && $request->filled('user_base_id')) {
                $userId = $request->user_base_id;
            }

            $sales = $this->saleService->getUserSales($userId, $request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'Sales fetched successfully.',
                data: SaleResource::collection($sales)->response()->getData(true),
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 500);
        }
    }


    public function store(StoreSaleRequest $request): JsonResponse
    {
        //Log::info($request->all());
        try {
            $userId = auth()->id();
            if (auth()->user()->user_type == UserType::SR && $request->filled('user_base_id')) {
                $userId = $request->user_base_id;
            }
            $sale = $this->saleService->createSaleFromCart($userId, $request->validated());
            $sale->load(['items.batchAllocations', 'allPayments', 'taxes', 'shippingAddress']);
            return $this->jsonResponse(true, $sale->status === 'quotation' ? 'Quotation created successfully.' : 'Sale created successfully.', new SaleResource($sale), 201);
        } catch (Exception $e) {
            Log::error($e);
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            $sale = $this->saleService->getSaleDetails($id, $userId);
            $sale->load(['items.batchAllocations', 'allPayments']);
            return $this->jsonResponse(true, 'Details fetched successfully.', new SaleResource($sale), 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 404);
        }
    }

    public function update(UpdateSaleRequest $request, int $id): JsonResponse
    {
        //Log::info($request->all());
        try {
            $userId = auth()->id();
            if (auth()->user()->user_type == UserType::SR && $request->filled('user_base_id')) {
                $userId = $request->user_base_id;
            }
            $sale = $this->saleEditService->editSale($id, $userId, $request->validated()['items'] ?? []);
            return $this->jsonResponse(true, 'Sale updated successfully.', new SaleResource($sale), 200);
        } catch (Exception $e) {
            Log::error($e);
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function confirmSaleFromQuotation(Request $request, int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            if (auth()->user()->user_type == UserType::SR && $request->filled('user_base_id')) {
                $userId = $request->user_base_id;
            }
            $sale = $this->saleService->confirmQuotation($id, $userId);
            return $this->jsonResponse(true, 'Quotation confirmed to Sale successfully.', new SaleResource($sale), 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function cancelQuotation( Request $request, int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            if (auth()->user()->user_type == UserType::SR && $request->filled('user_base_id')) {
                $userId = $request->user_base_id;
            }
            $sale = $this->saleService->cancelQuotation($id, $userId);
            return $this->jsonResponse(true, 'Quotation cancelled successfully.', new SaleResource($sale), 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function submitPayment(SubmitPaymentRequest $request, int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            if (auth()->user()->user_type == UserType::SR && $request->filled('user_base_id')) {
                $userId = $request->user_base_id;
            }

            $this->saleService->submitPayment($id, $userId, $request->validated(), $request->file('document'));
            return $this->jsonResponse(true, 'Payment submitted Successfully.', null, 200);
            return $this->jsonResponse(true, 'Payment submitted for verification.', null, 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function summaryReport(Request $request): JsonResponse
    {
        try {
            $userId = auth()->id();
            if (auth()->user()->user_type == UserType::SR && $request->filled('user_base_id')) {
                $userId = $request->user_base_id;
            }
            $summary = $this->saleService->getSummaryReport($userId);
            return $this->jsonResponse(true, 'Summary report retrieved successfully.', $summary, 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 422);
        }
    }

    public function returnSale(StoreSaleReturnRequest $request, int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            if (auth()->user()->user_type == UserType::SR && $request->filled('user_base_id')) {
                $userId = $request->user_base_id;
            }
            $saleReturn = $this->saleReturnService->createReturn(
                $id,
                $userId,
                $request->validated()['items'],
                $request->validated()['reason'] ?? 'Customer Return'
            );

            return $this->jsonResponse(
                true,
                'Items returned successfully.',
                $saleReturn->load('sale'),
                200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }
    public function cancelConfirmedSale(Request $request, int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            if (auth()->user()->user_type == UserType::SR && $request->filled('user_base_id')) {
                $userId = $request->user_base_id;
            }
            $this->saleService->cancelConfirmedSale($id, $userId, $request->reason ?? 'No need now');
            return $this->jsonResponse(true, 'Confirmed sale cancelled successfully.', null, 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function downloadDirectInvoice(int $id)
    {
        try {
            $userId = auth()->id();
            $sale = $this->saleService->getSaleDetails($id, $userId);
            $sale->load(['items.product', 'items.variation', 'customer', 'allPayments', 'shippingAddress']);
            $data = [
                'sale' => $sale,
                'company_name' => 'Original Ponno',
                // 🟢 NOTE: এখানে ডাটাবেস থেকে কোম্পানির আসল ঠিকানা, ফোন, লোগো আনো (Settings Table থেকে)
                'company_address' => 'Mirpur-1, Dhaka-1216 • BIN: 004829104-0203',
                'company_phone' => '+880 1712-794033',
            ];
            // Load PDF View
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.template', compact('sale', 'data'));

            // Stream or Download
            return $pdf->download('Invoice-' . $sale->invoice_no . '.pdf');
            // যদি মোবাইল অ্যাপ থেকে ডাউনলোড করতে চাও, তবে download() দিলেই হবে। 
        } catch (\Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 500);
        }
    }
    
    public function getInvoiceDownloadUrl(int $id): JsonResponse
    {
        try {
            $url = URL::temporarySignedRoute(
                'invoice.download',
                now()->addHours(1),
                ['id' => $id]
            );

            return $this->jsonResponse(true, 'Download URL generated.', ['download_url' => $url], 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function downloadInvoice(int $id, InvoiceService $invoiceService)
    {
        $sale = Sale::findOrFail($id);
        return $invoiceService->generatePdf($sale);
    }
}
