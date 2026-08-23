<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\V1\App\ProductVariationResource;
use App\Http\Resources\Api\V1\App\ProductImageResource;
use Illuminate\Support\Facades\Log;

class ProductResource extends JsonResource
{
    /**
     * Override collection method to flatten variable products into individual items.
     */
    public static function collection($resource)
    {
        $collection = $resource->getCollection()->flatMap(function ($product) {

            if ($product->type === 'variable' && $product->relationLoaded('variations') && $product->variations->isNotEmpty()) {
                return $product->variations->map(function ($variant) use ($product) {
                    $stockQty = $variant->relationLoaded('stocks') ? $variant->stocks->sum('qty_available') : 0;

                    return [
                        'id'           => $product->id,
                        'parent_id'    => $product->id,
                        'variation_id' => $variant->id,
                        'is_variant'   => true,
                        'type'         => 'variable',
                        'name'         => $product->name . ' - ' . $variant->name,
                        'name_bangla'  => $product->name_bangla,
                        'slug'         => $product->slug,
                        'sku'          => $variant->sub_sku,
                        'parent_sku'   => $product->sku,
                        'image_url'    => $product->image ? getImage('products', $product->image) : null,
                        'sell_price'   => (float) ($variant->sell_price ?? $product->sell_price),
                        'mrp_price'    => (float) (($variant->sell_price ?? $product->sell_price) + 20),
                        'category_id'  => $product->category_id,
                        'brand_id'     => $product->brand_id,
                        'is_feature'   => (bool) $product->is_feature,
                        'stock_qty'    => $stockQty,
                        'images'       => ProductImageResource::collection($product->images),
                        //'created_at'   => $product->created_at,
                    ];
                });
            }

            return [[
                'id'           => $product->id,
                'parent_id'    => $product->id,
                'variation_id' => null,
                'is_variant'   => false,
                'type'         => 'single',
                'name'         => $product->name,
                'name_bangla'  => $product->name_bangla,
                'slug'         => $product->slug,
                'sku'          => $product->sku,
                'parent_sku'   => $product->sku,
                'image_url'    => $product->image ? getImage('products', $product->image) : null,
                'sell_price'   => (float) $product->sell_price,
                'mrp_price'    => (float) ($product->sell_price + 20),
                'category_id'  => $product->category_id,
                'brand_id'     => $product->brand_id,
                'is_feature'   => (bool) $product->is_feature,
                'stock_qty'    => 0,
                'images'       => ProductImageResource::collection($product->images),
                'created_at'   => $product->created_at,
            ]];
        });
        $resource->setCollection($collection);

        return parent::collection($resource);
    }

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
    //This product resource class is used for showing product list [limited data]
   /* public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'name_bangla' => $this->name_bangla,
            'slug'        => $this->slug,
            'sku'         => $this->sku,
            'image_url'   => $this->image ? getImage('products', $this->image) : null,
            'sell_price'  => $this->sell_price,
            'mrp_price'   => $this->sell_price + 20,
            //'min_price'   => $this->min_price,
            //'max_price'   => $this->max_price,
            'type'        => $this->type,
            'category_id'  => $this->category_id,
            'brand_id'     => $this->brand_id,
            'is_feature'   => (bool) $this->is_feature,
            'images'      => ProductImageResource::collection($this->whenLoaded('images')),
            'variations'  => $this->type == 'variable' ? ProductVariationResource::collection($this->whenLoaded('variations')) : false,
            'created_at'  => $this->created_at,
        ];
    }
    */
}