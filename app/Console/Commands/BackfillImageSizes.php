<?php
namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;

class BackfillImageSizes extends Command
{
    protected $signature = 'images:backfill-sizes {--chunk=100}';
    protected $description = 'Backfill image sizes for existing products and gallery images';

    public function handle()
    {
        $this->info('Starting image size backfill...');
        
        $this->backfillMainImages();
        
        $this->backfillGalleryImages();
        
        $this->info('Image size backfill completed!');
    }

    private function backfillMainImages()
    {
        $chunkSize = $this->option('chunk');
        $updated = 0;
        
        Product::whereNull('image_size')
            ->chunk($chunkSize, function ($products) use (&$updated) {
                foreach ($products as $product) {
                    if ($product->image) {
                        $imagePath = public_path('/products/'.$product->image);
                        if (file_exists($imagePath)) {
                            $size = filesize($imagePath);
                            $product->image_size = $size;
                            $product->save();
                            $updated++;
                        }
                    }
                }
            });
            
        $this->info("Main images updated: {$updated}");
    }

    private function backfillGalleryImages()
    {
        $chunkSize = $this->option('chunk');
        $updated = 0;
        
        ProductImage::whereNull('image_size')
            ->chunk($chunkSize, function ($images) use (&$updated) {
                foreach ($images as $image) {
                    if ($image->image) {
                        $imagePath = public_path('/products/'.$image->image);
                        if (file_exists($imagePath)) {
                            $size = filesize($imagePath);
                            $image->image_size = $size;
                            $image->save();
                            $updated++;
                        }
                    }
                }
            });
            
        $this->info("Gallery images updated: {$updated}");
    }
}