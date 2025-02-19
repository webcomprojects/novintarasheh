<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InsertFirstTimeProductAccounting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function isOneDigit($number)
    {
        return ($number >= -9 && $number <= 9);
    }

    public $tries = 5;
    public $timeout = 300;
    
    public function handle()
    {
        // if (!checkApiAccounting()) {
        //     return false;
        // }
        $client = new \GuzzleHttp\Client();

        try {
            // $response = $client->request('GET', 'http://127.0.0.1:5000/api/products');
            $response = $client->request('GET', 'http://109.122.229.114:5000/api/products');
            // $response = $client->request('GET', 'http://2.187.99.27:5000/api/products');
            $response = $response->getBody()->getContents();
            $response = json_decode($response, true);

            //$productId = Setting::where('key', 'productId')->pluck('value')->first();
            //$productIds = Post::buildCode();
            // if ($response->status == 200) {
            option_update('last_time_insertFirstTime_product', now());
            foreach ($response as $article) {

                if (!Product::where('fldId', $article['A_Code'])->exists()) {

                    $fldId = $article['A_Code'];
                    $fldC_Kala = $article['A_Code'];
                    $title = $article['A_Name'];
                    $vahed_kol = '';
                    $Vahed = $article['vahed'];
                    $price = $article['Sel_Price'];
                    $offPrice = $article['PriceTakhfif'] > 0 ? $article['PriceTakhfif'] : $article['Sel_Price'];
                    $morePriceArray = [
                        "fldTipFee1" => $article['Sel_Price'] ?: 0,
                        "fldTipFee2" => $offPrice ?: 0,
                        "fldTipFee3" => $article['Sel_Price3'] ?: 0,
                        "fldTipFee4" => $article['Sel_Price4'] ?: 0,
                        "fldTipFee5" => $article['Sel_Price5'] ?: 0,
                        "fldTipFee6" => $article['Sel_Price6'] ?: 0,
                        "fldTipFee7" => $article['Sel_Price7'] ?: 0,
                        "fldTipFee8" => $article['Sel_Price8'] ?: 0,
                        "fldTipFee9" => $article['Sel_Price9'] ?: 0,
                        "fldTipFee10" => $article['Sel_Price10'] ?: 0,
                    ];

                    $morePrice = json_encode($morePriceArray);
                    // $count = explode('/', $article->fldMande);
                    $count = $article['Exist'];

                    $fldTedadKarton = $article['Karton'];
                    $status = $article['IsActive'] == "true" ? 1 : 0;
                    $image = '';
                    // $fldPorForoosh = $article->fldPorForoosh;

                    $Mcategory = Category::where('fldC_M_GroohKala', $article['Main_Category']['M_groupcode'])->first();

                    $product = new Product();
                    $product->fldId = $fldId;
                    $product->fldC_Kala = $fldC_Kala;
                    $product->title = $title;
                    $product->slug = $title;
                    $product->vahed_kol = $vahed_kol;
                    $product->vahed = $Vahed;
                    $product->unit = $Vahed ?: $vahed_kol;
                    $product->morePrice = $morePrice;
                    $product->fldTedadKarton = $fldTedadKarton;
                    $product->published = $status;
                    $product->image = $image;
                    $product->type = "physical";
                    $product->currency_id = 1;
                    $product->category_id = $Mcategory->id;
                    ///$product->product_id=$productId . '-' . $productIds;
                    $product->save();

                    if (!empty($article['Main_Category']) && !empty($article['Sub_Category'])) {
                        $Scategory = Category::where(['fldC_S_GroohKala' => $article['Sub_Category']['S_groupcode'], 'fldC_M_GroohKala' => $article['Main_Category']['M_groupcode']])->first();
                        $product->categories()->sync([$Mcategory->id, $Scategory->id]);
                    } else {
                        $product->categories()->sync([$Mcategory->id]);
                    }

                    for ($i = 1; $i <= 10; $i++) {
                        $title = "fldTipFee" . $i;
                        $fldTipFee = $morePriceArray[$title];

                        $discount = 0;
                        $discount_price = $fldTipFee;
                        if ($title == "fldTipFee2") {
                            $discount = (($price - $offPrice) / $price) * 100;
                            $discount_price = $offPrice;
                        }
                        //$discount=0;
                        //$discount_price=$fldTipFee;
                        $product->prices()->create(
                            [
                                "title" => $title,
                                "price" => $fldTipFee,
                                "discount" => $discount,
                                "discount_price" => $discount_price,
                                "stock" => $count,
                                "stock_carton" => $fldTedadKarton,
                                "accounting" => 1,
                            ]
                        );
                    }
                    //$product->$fldPorForoosh=$fldPorForoosh;
                }
            }
            // }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
        }

        option_update('last_time_insertFirstTime_product', now());
    }
}
