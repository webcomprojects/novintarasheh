<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Log;

class GetUpdateProductsAccounting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct()
    {
        //
    }

    public function handle()
    {
        
        // if (!checkApiAccounting()) {
        //     return false;
        // }
        $client = new \GuzzleHttp\Client();
        // $requestGmailData = [
        //     'headers' => [
        //         'apiKey' => option('get_product_apikey'),
        //     ],
        // ];

        try {
            // $response = $client->request('GET', 'http://127.0.0.1:5000/api/products');
            $response = $client->request('GET', 'http://srv3.noipservice.ir:7068/api/updated/products');
            // $response = $client->request('GET', 'http://2.187.99.27:5000/api/updated/products');

            $response = $response->getBody()->getContents();
            $response = json_decode($response, true);


            // if ($response->status == 200) {
            foreach ($response as $article) {

                $product_exist = Product::where('fldId', $article['A_Code'])->with('prices')->first();
                if ($product_exist) {

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

                        for ($i = 1; $i <= 10; $i++) {
                            $titleFldTipFee = "fldTipFee" . $i;
                            $fldTipFee = $morePriceArray[$titleFldTipFee];

                            $discount = 0;
                            $discount_price = $fldTipFee;
                            if ($titleFldTipFee == "fldTipFee2") {
                                $discount = (($price - $offPrice) / $price) * 100;
                                $discount_price = $offPrice;
                                $fldTipFee = $price;
                            }

                            Price::withTrashed()->where(['product_id' => $product_exist->id, 'title' => $titleFldTipFee])->update([
                                "price" => $fldTipFee,
                                "discount" => $discount,
                                "discount_price" => $discount_price,
                                "stock" => $count,
                                "stock_carton" => $fldTedadKarton,
                                "accounting" => 1,
                                "deleted_at" => null,
                            ]);
                        }

                        $Mcategory = Category::where('fldC_M_GroohKala', $article['Main_Category']['M_groupcode'])->first();

                        $product_exist->fldId = $fldId;
                        $product_exist->fldC_Kala = $fldC_Kala;
                        $product_exist->title = $title;
                        $product_exist->slug = $title;
                        $product_exist->vahed_kol = $vahed_kol;
                        $product_exist->vahed = $Vahed;
                        $product_exist->unit = $Vahed ?: $vahed_kol;
                        $product_exist->morePrice = $morePrice;
                        $product_exist->fldTedadKarton = $fldTedadKarton;
                        $product_exist->published = $status;
                        $product_exist->image = $image;
                        $product_exist->type = "physical";
                        $product_exist->category_id = $Mcategory->id;
                        ///$product->product_id=$productId . '-' . $productIds;
                        $product_exist->save();


                        if (!empty($article['Main_Category']) && !empty($article['Sub_Category'])) {
                            $Scategory = Category::where(['fldC_S_GroohKala' => $article['Sub_Category']['S_groupcode'], 'fldC_M_GroohKala' => $article['Main_Category']['M_groupcode']])->first();
                            $product_exist->categories()->sync([$Mcategory->id, $Scategory->id]);
                        } else {
                            $product_exist->categories()->sync([$Mcategory->id]);
                        }

                        //$product->$fldPorForoosh=$fldPorForoosh;
                }


            }
            Product::clearCache();
            // }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return false;
        }
    }
}