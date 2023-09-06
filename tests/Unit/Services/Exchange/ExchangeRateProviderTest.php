<?php
declare(strict_types=1);

namespace Services\Exchange;

use App\Services\CacheService;
use App\Services\Exchange\ExchangeRateProvider;
use App\Services\Exchange\ExchangeRateProviderFactory;
use App\Services\Exchange\ExchangeService;
use App\ValueObjects\Currency;
use App\ValueObjects\ExchangeRate;
use HttpResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class ExchangeRateProviderTest extends TestCase
{
    use RefreshDatabase;


    public function testGetExchangeRate()
    {
        // Mock the HTTP response
        Http::fake([
            'https://api.exchangerate.host/latest' => Http::response($this->getExchangeRateApiResponse(), 200),
        ]);

        // Replace with your actual implementation for creating the ExchangeRateProviderFactory
        $providerFactory = new ExchangeRateProviderFactory();

        // Create an instance of ExchangeService
        $exchangeService = new ExchangeService($providerFactory);

        // Define your test currencies
        $fromCurrency = new Currency('EUR');
        $toCurrency = new Currency('AZN');

        // Get the exchange rate
        $exchangeRate = $exchangeService->getExchangeRate($fromCurrency, $toCurrency);

        // Perform assertions on $exchangeRate
        $this->assertInstanceOf(ExchangeRate::class, $exchangeRate);
        $this->assertEquals($fromCurrency, $exchangeRate->getFromCurrency());
        $this->assertEquals($toCurrency, $exchangeRate->getToCurrency());
//        $this->assertIsInt($exchangeRate->getRate()->getAmount());
        // Add more assertions as needed
    }

    private function getExchangeRateApiResponse(): string
    {
        // Replace this with the actual response data you expect from the API
        return '{
            "motd": {"msg": "..."},
            "success": true,
            "base": "EUR",
            "date": "2023-09-03",
            "rates": {"AED":3.962107,"AFN":87.865244,"ALL":107.750398,"AMD":416.223961,"ANG":1.944162,"AOA":889.829035,"ARS":377.15779,"AUD":1.669255,"AWG":1.941979,"AZN":1.834187,"BAM":1.945546,"BBD":2.158085,"BDT":117.843446,"BGN":1.957807,"BHD":0.40785,"BIF":3053.089013,"BMD":1.079827,"BND":1.457204,"BOB":7.454583,"BRL":5.336908,"BSD":1.07982,"BTC":0.000042,"BTN":89.253973,"BWP":14.566875,"BYN":2.723404,"BZD":2.17513,"CAD":1.464391,"CDF":2676.107235,"CHF":0.955949,"CLF":0.033891,"CLP":921.863126,"CNH":7.838601,"CNY":7.832484,"COP":4429.771589,"CRC":581.562354,"CUC":1.079287,"CUP":27.774174,"CVE":109.655237,"CZK":24.109903,"DJF":191.80224,"DKK":7.458428,"DOP":61.230149,"DZD":147.479145,"EGP":33.300628,"ERN":16.178971,"ETB":59.601735,"EUR":1,"FJD":2.438476,"FKP":0.857305,"GBP":0.857143,"GEL":2.840112,"GGP":0.85718,"GHS":12.330036,"GIP":0.857465,"GMD":65.591877,"GNF":9262.52302,"GTQ":8.489136,"GYD":225.674248,"HKD":8.459907,"HNL":26.561473,"HRK":7.542547,"HTG":146.266644,"HUF":384.557263,"IDR":16456.667221,"ILS":4.095063,"IMP":0.85773,"INR":89.224052,"IQD":1412.633022,"IRR":45569.941995,"ISK":143.031017,"JEP":0.857209,"JMD":166.413852,"JOD":0.7647,"JPY":157.693444,"KES":157.14965,"KGS":95.193403,"KHR":4486.011106,"KMF":492.449201,"KPW":970.721404,"KRW":1422.052579,"KWD":0.332474,"KYD":0.899369,"KZT":493.561703,"LAK":21245.155726,"LBP":16212.011662,"LKR":345.169086,"LRD":200.611532,"LSL":20.201502,"LYD":5.195007,"MAD":11.026116,"MDL":19.177915,"MGA":4868.861123,"MKD":61.44878,"MMK":2265.183021,"MNT":3721.095822,"MOP":8.717016,"MRU":40.972196,"MUR":48.989555,"MVR":16.610268,"MWK":1162.757113,"MXN":18.435976,"MYR":5.012595,"MZN":68.846167,"NAD":20.326513,"NGN":836.442401,"NIO":39.470138,"NOK":11.497475,"NPR":142.805985,"NZD":1.81121,"OMR":0.415926,"PAB":1.079626,"PEN":3.983625,"PGK":3.948726,"PHP":61.255423,"PKR":330.319689,"PLN":4.47176,"PYG":7850.304135,"QAR":3.932702,"RON":4.950067,"RSD":117.332934,"RUB":103.981334,"RWF":1283.553029,"SAR":4.045634,"SBD":9.028169,"SCR":14.217408,"SDG":648.76496,"SEK":11.905663,"SGD":1.460244,"SHP":0.857164,"SLL":22617.252288,"SOS":614.278934,"SRD":41.202854,"SSP":140.49629,"STD":24032.670578,"STN":24.364582,"SVC":9.438738,"SYP":2709.961899,"SZL":20.190463,"THB":37.789755,"TJS":11.84944,"TMT":3.786,"TND":3.336669,"TOP":2.571866,"TRY":28.798862,"TTD":7.329584,"TWD":34.383758,"TZS":2701.839801,"UAH":39.837442,"UGX":4011.692613,"USD":1.079318,"UYU":40.563013,"UZS":13053.315863,"VES":35.23535,"VND":25977.562735,"VUV":128.051167,"WST":2.932662,"XAF":655.853404,"XAG":0.045336,"XAU":0.001106,"XCD":2.915328,"XDR":0.813158,"XOF":655.853078,"XPD":0.001864,"XPF":119.31387,"XPT":0.001673,"YER":269.909311,"ZAR":20.326366,"ZMW":21.815758,"ZWL":347.302706}
        }';
    }
}
