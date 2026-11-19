<?php

namespace App\Http\Swagger;

use App\Http\Requests\Api\V1\App\Sale\SaleListRequest;
use App\Http\Requests\Api\V1\App\Sale\StoreSaleRequest;
use App\Http\Requests\Api\V1\App\Sale\StoreSaleReturnRequest;
use App\Http\Requests\Api\V1\App\Sale\SubmitPaymentRequest;
use App\Http\Requests\Api\V1\App\Sale\UpdateSaleRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

interface SaleApiDocInterface
{
    #[OA\Get(
        path: "/api/v1/app/orders",
        summary: "Get List of User Sales & Quotations",
        description: "Fetch paginated sales/quotations with status, date, and invoice filters. Supports SR mode via user_base_id.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "user_base_id", in: "query", required: false, schema: new OA\Schema(type: "integer"), description: "Target User ID (Required when logged in as SR)"),
            new OA\Parameter(name: "status", in: "query", required: false, schema: new OA\Schema(type: "string"), description: "Filter by status (e.g. draft, quotation, confirmed, cancelled)"),
            new OA\Parameter(name: "date_from", in: "query", required: false, schema: new OA\Schema(type: "string", format: "date")),
            new OA\Parameter(name: "date_to", in: "query", required: false, schema: new OA\Schema(type: "string", format: "date")),
            new OA\Parameter(name: "search", in: "query", required: false, schema: new OA\Schema(type: "string"), description: "Search by Invoice No"),
            new OA\Parameter(name: "per_page", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 15))
        ],
        responses: [
            new OA\Response(response: 200, description: "Sales fetched successfully"),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 500, description: "Server Error")
        ]
    )]
    public function index(SaleListRequest $request);

    #[OA\Post(
        path: "/api/v1/app/orders/quotation",
        summary: "Create New Sale / Quotation from Cart",
        description: "Convert active DB cart items into a Sale or Quotation transaction. Status determines the transaction type.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["cart_id", "shipping_address_id"],
                properties: [
                    new OA\Property(property: "user_base_id", type: "integer", example: 2, nullable: true, description: "Required when logged in as SR"),
                    new OA\Property(property: "cart_id", type: "integer", example: 12),
                    new OA\Property(property: "shipping_address_id", type: "integer", example: 10, nullable: true),
                    new OA\Property(property: "status", type: "string", enum: ["draft", "quotation", "confirmed"], example: "quotation"),
                    new OA\Property(property: "channel", type: "string", enum: ["admin_pos", "web_ecommerce", "mobile_dealer_app", "sr"], example: "mobile_dealer_app"),
                    new OA\Property(property: "warehouse_id", type: "integer", example: 1, nullable: true),
                    new OA\Property(property: "note", type: "string", example: "Please deliver within 3 days"),
                    new OA\Property(property: "shipping_charge", type: "number", format: "float", example: 100.00),
                    new OA\Property(
                        property: "payments",
                        type: "array",
                        nullable: true,
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "amount", type: "number", format: "float", example: 2500.00),
                                new OA\Property(property: "method", type: "string", example: "bkash"),
                                new OA\Property(property: "reference_no", type: "string", example: "TRX987654321", nullable: true)
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Sale / Quotation created successfully"),
            new OA\Response(response: 400, description: "Validation Error / Empty Cart"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function store(StoreSaleRequest $request);

    #[OA\Get(
        path: "/api/v1/app/orders/summary-report",
        summary: "Get User Sale Summary Report",
        description: "Retrieves complete sale & payment summary report for the logged in user or a target user (SR Mode).",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "user_base_id",
                in: "query",
                required: false,
                description: "Target User ID (Required when logged in as SR/Admin)",
                schema: new OA\Schema(type: "integer", example: 10)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Sale summary report fetched successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Summary report retrieved successfully."),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "total_sales", type: "integer", example: 20),
                                new OA\Property(property: "total_quotations", type: "integer", example: 5),
                                new OA\Property(property: "confirmed_sales", type: "integer", example: 12),
                                new OA\Property(property: "draft_sales", type: "integer", example: 3),
                                new OA\Property(property: "cancelled_sales", type: "integer", example: 1),
                                new OA\Property(property: "full_paid_sales", type: "integer", example: 10),
                                new OA\Property(property: "partial_paid_sales", type: "integer", example: 2),
                                new OA\Property(property: "unpaid_sales", type: "integer", example: 3),
                                new OA\Property(property: "total_sale_amount", type: "number", format: "float", example: 50000.00),
                                new OA\Property(property: "total_paid_amount", type: "number", format: "float", example: 40000.00),
                                new OA\Property(property: "total_due_amount", type: "number", format: "float", example: 10000.00),
                                new OA\Property(property: "total_discount_amount", type: "number", format: "float", example: 2500.00),
                                new OA\Property(property: "total_tax_amount", type: "number", format: "float", example: 1500.00),
                                new OA\Property(property: "total_shipping_amount", type: "number", format: "float", example: 800.00)
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function summaryReport(Request $request);

    #[OA\Get(
        path: "/api/v1/app/orders/{id}",
        summary: "Get Single Sale/Quotation Details",
        description: "Fetch full sale details with items, batch allocations, and payments.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Sale details fetched successfully"),
            new OA\Response(response: 404, description: "Sale not found")
        ]
    )]
    public function show(int $id);

    #[OA\Put(
        path: "/api/v1/app/orders/{id}",
        summary: "Update Sale / Quotation Items & Note",
        description: "Allows updating items, quantities, unit price, discount and note of an editable sale/quotation.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "note", type: "string", example: "Updated note for delivery schedule"),
                    new OA\Property(
                        property: "items",
                        type: "array",
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "product_id", type: "integer", example: 10),
                                new OA\Property(property: "variation_id", type: "integer", example: 5),
                                new OA\Property(property: "quantity", type: "integer", example: 3),
                                new OA\Property(property: "unit_price", type: "number", format: "float", example: 150.00),
                                new OA\Property(property: "discount", type: "number", format: "float", example: 10.00, nullable: true)
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Sale updated successfully"),
            new OA\Response(response: 400, description: "Cannot update non-editable sale")
        ]
    )]
    public function update(UpdateSaleRequest $request, int $id);

    #[OA\Post(
        path: "/api/v1/app/orders/{id}/confirm",
        summary: "Confirm Quotation to Final Sale",
        description: "Convert a quotation into a confirmed sale, ready for payment.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Quotation confirmed to Sale successfully"),
            new OA\Response(response: 400, description: "Invalid transaction state")
        ]
    )]
    public function confirmSaleFromQuotation(Request $request, int $id);

    #[OA\Post(
        path: "/api/v1/app/orders/{id}/cancel/quotation",
        summary: "Cancel Quotation",
        description: "Cancel a pending quotation belonging to the user.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Quotation cancelled successfully"),
            new OA\Response(response: 400, description: "Cannot cancel in current state")
        ]
    )]
    public function cancelQuotation(Request $request, int $id);

    #[OA\Post(
        path: "/api/v1/app/orders/{id}/cancel-sale",
        summary: "Cancel Confirmed Sale",
        description: "Cancel an already confirmed sale. Typically requires a reason and may trigger refund/return workflow depending on payment status.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["reason"],
                properties: [
                    new OA\Property(property: "reason", type: "string", example: "Customer changed mind after order confirmation"),
                    new OA\Property(property: "note", type: "string", example: "Requested cancellation within 24h", nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Confirmed sale cancelled successfully"),
            new OA\Response(response: 400, description: "Sale cannot be cancelled in current state (e.g. already shipped/delivered)"),
            new OA\Response(response: 404, description: "Sale not found")
        ]
    )]
    public function cancelConfirmedSale(Request $request, int $id);

    #[OA\Post(
        path: "/api/v1/app/orders/{id}/payment",
        summary: "Submit Manual Payment Info",
        description: "Upload bank/mobile banking receipt image along with Transaction ID for a sale.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["method", "amount"],
                    properties: [
                        new OA\Property(property: "method", type: "string", example: "bkash"),
                        new OA\Property(property: "amount", type: "number", format: "float", example: 2500.00),
                        new OA\Property(property: "transaction_no", type: "string", example: "TRX987654321"),
                        new OA\Property(property: "account_no", type: "string", example: "01700000000", nullable: true),
                        new OA\Property(property: "bank_name", type: "string", example: "City Bank", nullable: true),
                        new OA\Property(property: "note", type: "string", example: "Paid via bKash Merchant", nullable: true),
                        new OA\Property(property: "document", type: "string", format: "binary", description: "Payment Receipt Image/Slip")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Payment submitted for verification"),
            new OA\Response(response: 400, description: "Validation Error")
        ]
    )]
    public function submitPayment(SubmitPaymentRequest $request, int $id);

    #[OA\Post(
        path: "/api/v1/app/orders/{id}/return",
        summary: "Return Sale Items",
        description: "Create a sale return for one or more items of an existing sale.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["items"],
                properties: [
                    new OA\Property(property: "reason", type: "string", example: "Damaged product"),
                    new OA\Property(
                        property: "items",
                        type: "array",
                        items: new OA\Items(
                            required: ["product_id", "quantity"],
                            properties: [
                                new OA\Property(property: "product_id", type: "integer", example: 10),
                                new OA\Property(property: "variation_id", type: "integer", example: 5, nullable: true),
                                new OA\Property(property: "quantity", type: "integer", example: 2),
                                new OA\Property(property: "reason", type: "string", example: "Damaged", nullable: true)
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Items returned successfully"),
            new OA\Response(response: 400, description: "Invalid return request")
        ]
    )]
    public function returnSale(StoreSaleReturnRequest $request, int $id);
    #[OA\Get(
        path: "/api/v1/app/orders/{id}/invoice-url",
        summary: "Get URL for download invoice",
        description: "Get generated URL for download sale invoice.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Sale invoice generated URL fetched successfully"),
            new OA\Response(response: 404, description: "Sale not found")
        ]
    )]
    public function downloadDirectInvoice(int $id);


    /* #[OA\Post(
        path: "/api/v1/app/orders/{id}/refund",
        summary: "Refund Sale Payment",
        description: "Initiate a monetary refund for a sale (full or partial). Returns refund amount & status.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["amount", "method"],
                properties: [
                    new OA\Property(property: "amount", type: "number", format: "float", example: 1500.00, description: "Refund amount (must not exceed due/refundable amount)"),
                    new OA\Property(property: "method", type: "string", example: "bkash", description: "Refund channel"),
                    new OA\Property(property: "reference_no", type: "string", example: "REF987654321", nullable: true),
                    new OA\Property(property: "note", type: "string", example: "Customer requested partial refund", nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Refund processed/submitted successfully"),
            new OA\Response(response: 400, description: "Invalid refund request / exceeds refundable amount"),
            new OA\Response(response: 404, description: "Sale not found")
        ]
    )]
    public function refundSale(int $id); */
}
