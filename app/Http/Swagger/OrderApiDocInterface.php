<?php

namespace App\Http\Swagger;

use App\Http\Requests\Api\V1\App\Order\CreateQuotationRequest;
use App\Http\Requests\Api\V1\App\Order\OrderListRequest;
use App\Http\Requests\Api\V1\App\Order\SubmitPaymentRequest;
use App\Http\Requests\Api\V1\App\Order\UpdateQuotationRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

interface OrderApiDocInterface
{
    #[OA\Get(
        path: "/api/v1/app/orders",
        summary: "Get List of User Orders & Quotations",
        description: "Fetch paginated orders/quotations with status, date, and invoice filters.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "status", in: "query", required: false, schema: new OA\Schema(type: "string"), description: "Filter by status (e.g. pending, approved, final, cancelled)"),
            new OA\Parameter(name: "date_from", in: "query", required: false, schema: new OA\Schema(type: "string", format: "date")),
            new OA\Parameter(name: "date_to", in: "query", required: false, schema: new OA\Schema(type: "string", format: "date")),
            new OA\Parameter(name: "search", in: "query", required: false, schema: new OA\Schema(type: "string"), description: "Search by Invoice No"),
            new OA\Parameter(name: "user_base_id", in: "query", required: false, schema: new OA\Schema(type: "integer"), description: "Target User ID (For SRs)"),
            new OA\Parameter(name: "per_page", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 15))
        ],
        responses: [
            new OA\Response(response: 200, description: "Orders fetched successfully"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(OrderListRequest $request);

    #[OA\Post(
        path: "/api/v1/app/orders/quotation",
        summary: "Submit New Quotation from Cart",
        description: "Convert active DB cart items into a Negotiable Quotation transaction.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "user_base_id", type: "integer", example: 2, nullable: true),
                    new OA\Property(property: "cart_id", type: "integer", example: 12, nullable: false),
                    new OA\Property(property: "note", type: "string", example: "Please offer best price for bulk quantity")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Quotation created successfully"),
            new OA\Response(response: 422, description: "Validation Error / Empty Cart")
        ]
    )]
    public function storeQuotation(CreateQuotationRequest $request);

    #[OA\Get(
        path: "/api/v1/app/orders/{id}",
        summary: "Get Single Order/Quotation Details",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Order details fetched successfully"),
            new OA\Response(response: 404, description: "Order not found")
        ]
    )]
    public function show(int $id);

    #[OA\Put(
        path: "/api/v1/app/orders/{id}",
        summary: "Update Pending Quotation",
        description: "Allows client to modify quotation notes or items while status is pending.",
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
                                new OA\Property(property: "unit_price", type: "number", format: "float", example: 150.00)
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Quotation updated successfully"),
            new OA\Response(response: 400, description: "Cannot update non-pending order")
        ]
    )]
    public function update(UpdateQuotationRequest $request, int $id);

    #[OA\Post(
        path: "/api/v1/app/orders/{id}/confirm",
        summary: "Confirm Quotation to Final Order",
        description: "Accept negotiated price & delivery charges to convert Quotation into Sale.",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Order confirmed successfully. Ready for payment."),
            new OA\Response(response: 400, description: "Invalid transaction state")
        ]
    )]
    public function confirmOrder(int $id);

    #[OA\Post(
        path: "/api/v1/app/orders/{id}/payment",
        summary: "Submit Manual Payment Info",
        description: "Upload bank/mobile banking receipt image along with Transaction ID.",
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
                    required: ["method", "amount", "transaction_no"],
                    properties: [
                        new OA\Property(property: "method", type: "string", example: "bkash"),
                        new OA\Property(property: "amount", type: "number", format: "float", example: 2500.00),
                        new OA\Property(property: "transaction_no", type: "string", example: "TRX987654321"),
                        new OA\Property(property: "account_no", type: "string", example: "01700000000"),
                        new OA\Property(property: "bank_name", type: "string", example: "City Bank"),
                        new OA\Property(property: "note", type: "string", example: "Paid via bKash Merchant"),
                        new OA\Property(property: "document", type: "string", format: "binary", description: "Payment Receipt Image/Slip")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Payment attachment submitted for approval"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function submitPayment(SubmitPaymentRequest $request, int $id);
}
