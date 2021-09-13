<?php

namespace App\Http\Controllers;

use App\Models\RecordsData;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class RECORDS extends Controller
{
    public function index() {
        $buildings = array();
        $floors = array();
        $data = Visitor::distinct()->get(['building_id']);
        foreach ($data as $item) {
            $buildId = (current($item->toArray()));
            $floor = Visitor::where('building_id',$buildId)
                ->distinct('floor_id')->get();
            foreach ($floor as $item) {
                $floorId = current($item->toArray());
                array_push($floors,$floorId);
            }
            $buildings["$buildId"] = $floors;
        }

        return view('index',compact('buildings'));
    }

    public function bfk(Request $request) {

        // aynı gün içinde 1 den fazla katı ziyaret eden
        ini_set("memory_limit",-1);
        $a = explode("-",explode("/",$request->path())[1]);
        $bina = (int) $a[0];
        $kat = (int) $a[1];
        echo "<h3>Aynı gün içinde $bina nolu binanın $kat katından ayrılıp tekrar dönenler</h3>";
        $data = Visitor::where("building_id",$bina)
            ->where("floor_id",$kat)
            //  * veritabanında created_at değerini timestamp yaptığımda 0, import ettiğimde 260000000100 değerini alıyorum
            //  * date'e ise izin vermiyor bu yüzden tarih işlemlerini yapamadım
            //  * mongo db de date işlemlerinde aşağıdaki yol doğru değil hatırlatması amacıyla ekledim
            //->where("created_at",">",date('d/m/Y H:i:s',strtotime("+1 day","created_at")))
            ->distinct("floor_id")
            ->groupBy('mac')
            ->get()
            ->take(3000000)
        ;
        foreach ($data as $items) {
            $item = current($items->toArray());
            print_r($item["mac"]);
            echo "<br>";
            /*
             * ŞÖYLE DE YAPILABİLİRDİ.
             * bina ve kat tan o kattaki kullanıcıların mac adreslerini alıp
             * where bina-kat-mac ve distinct floor_id diyip 1 den fazla ise
             * ekranan yazdırma doğru sonuç verebilir ama hız konusunda negatif etkiler
             * tarih'te aynı şekilde bina-kat-mac adresi alınmış kullanıcının
             * tarih değerine 1 gün ekleyip sorgulatılabilirdi ama veritabanı işlemlerinde
             * veritabanı dışına çıkıp kod ile işlem yapmak yavaşlığa neden olur.
             * her veriyi önce çekip sonra tek tek aratmak yanlış bir yol olur diye yapmadım.
             */
        }

        exit();
        return view('birdenFazlaKat',compact("bina","kat"));
    }

    public function zyp(Request $request) {

        // 2 dk içinde farklı kata gidip gelen (Zıplama)
        ini_set("memory_limit",-1);
        $a = explode("-",explode("/",$request->path())[1]);
        $bina = (int) $a[0];
        $kat = (int) $a[1];
        echo "<h3>$bina nolu binanın $kat. katından zıplama yapanlar</h3>";
        $data = Visitor::where("building_id",$bina)
            ->where("floor_id",$kat)
            //  * veritabanında created_at değerini timestamp yaptığımda 0, import ettiğimde 260000000100 değerini alıyorum
            //  * date'e ise izin vermiyor bu yüzden tarih işlemlerini yapamadım
            //  * mongo db de date işlemlerinde aşağıdaki yol doğru değil hatırlatması amacıyla ekledim
            //->where("created_at","<","created_at + 2 minutes");
            ->distinct("floor_id")
            ->groupBy("mac")
            ->get()
            ->take(3000000)
        ;

        foreach ($data as $items) {
            $item = current($items->toArray());
            print_r($item["mac"]);
            echo "<br>";
            /*
             * ŞÖYLE DE YAPILABİLİRDİ.
             * bina ve kat tan o kattaki kullanıcıların mac adreslerini alıp
             * where bina-kat-mac ve distinct floor_id diyip 1 den fazla ise
             * ekranan yazdırma doğru sonuç verebilir ama hız konusunda negatif etkiler
             * tarih'te aynı şekilde bina-kat-mac adresi alınmış kullanıcının
             * tarih değerine 1 gün ekleyip sorgulatılabilirdi ama veritabanı işlemlerinde
             * veritabanı dışına çıkıp kod ile işlem yapmak yavaşlığa neden olur.
             * her veriyi önce çekip sonra tek tek aratmak yanlış bir yol olur diye yapmadım.
             */
        }

        exit();
        return view('ziplamaYapanlar',compact("bina","kat"));
    }
}
