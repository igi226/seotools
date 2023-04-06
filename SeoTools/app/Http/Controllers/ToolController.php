<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessBase64ConverterRequest;
use App\Http\Requests\ProcessBinaryConverterRequest;
use App\Http\Requests\ProcessCaseConverterRequest;
use App\Http\Requests\ProcessColorConverterRequest;
use App\Http\Requests\ProcessCssMinifierRequest;
use App\Http\Requests\ProcessDnsLookupRequest;
use App\Http\Requests\ProcessDomainIpLookupRequest;
use App\Http\Requests\ProcessHtmlMinifierRequest;
use App\Http\Requests\ProcessIdnConverterRequest;
use App\Http\Requests\ProcessJsonValidatorRequest;
use App\Http\Requests\ProcessNumberGeneratorRequest;
use App\Http\Requests\ProcessRedirectCheckerRequest;
use App\Http\Requests\ProcessReverseIpLookupRequest;
use App\Http\Requests\ProcessTextCleanerRequest;
use App\Http\Requests\ProcessIndexedPagesCheckerRequest;
use App\Http\Requests\ProcessIpLookupRequest;
use App\Http\Requests\ProcessJsMinifierRequest;
use App\Http\Requests\ProcessTextReplacerRequest;
use App\Http\Requests\ProcessUtmBuilderRequest;
use App\Http\Requests\ProcessWordDensityCounter;
use App\Http\Requests\ProcessKeywordResearchRequest;
use App\Http\Requests\ProcessLoremIpsumGeneratorRequest;
use App\Http\Requests\ProcessMd5GeneratorRequest;
use App\Http\Requests\ProcessPasswordGeneratorRequest;
use App\Http\Requests\ProcessQrGeneratorRequest;
use App\Http\Requests\ProcessSerpCheckerRequest;
use App\Http\Requests\ProcessSslCheckerRequest;
use App\Http\Requests\ProcessTextReverserRequest;
use App\Http\Requests\ProcessTextToSlugConverterRequest;
use App\Http\Requests\ProcessUrlConverterRequest;
use App\Http\Requests\ProcessUrlParserRequest;
use App\Http\Requests\ProcessUserAgentParserRequest;
use App\Http\Requests\ProcessWebsiteStatusCheckerRequest;
use App\Http\Requests\ProcessWhoisLookupRequest;
use App\Http\Requests\ProcessWordCounterRequest;
use Faker\Provider\Lorem;
use GeoIp2\Database\Reader as GeoIP;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\TransferStats;
use hexydec\css\cssdoc;
use hexydec\html\htmldoc;
use hexydec\jslite\jslite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Iodev\Whois\Factory as Whois;
use Spatie\SslCertificate\SslCertificate;
use WhichBrowser\Parser as UserAgent;

class ToolController extends Controller
{
    /**
     * List the Projects.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('tools.list', []);
    }

    /**
     * Show the SERP Checker form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function serpChecker(Request $request)
    {
        // If the Google Custom Search API is not enabled
        if (!config('settings.gcs')) {
            abort(404);
        }

        return view('tools.container', ['view' => 'serp-checker']);
    }

    /**
     * Process the SERP Checker.
     *
     * @param ProcessSerpCheckerRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processSerpChecker(ProcessSerpCheckerRequest $request)
    {
        // If the Google Custom Search API is not enabled
        if (!config('settings.gcs')) {
            abort(404);
        }

        $httpClient = new HttpClient();

        $results = false;
        try {
            $searchRequest = $httpClient->request('GET', 'https://www.googleapis.com/customsearch/v1?key=' . urlencode(config('settings.gcs_key')) . '&cx=' . config('settings.gcs_id') . '&gl=' . urlencode($request->input('country')) . '&q=' . urlencode($request->input('keyword')), [
                'http_errors' => false,
                'timeout' => config('settings.request_timeout'),
                'allow_redirects' => [
                    'max'             => 10,
                    'strict'          => true,
                    'referer'         => true,
                    'protocols'       => ['http', 'https']
                ],
                'headers' => [
                    'User-Agent' => config('settings.request_user_agent')
                ]
            ]);

            $results = json_decode($searchRequest->getBody()->getContents(), true);
        } catch (\Exception $e) {}

        return view('tools.container', ['view' => 'serp-checker', 'keyword' => $request->input('keyword'), 'domain' => $request->input('domain'), 'country' => $request->input('country'), 'results' => $results]);
    }

    /**
     * Show the Indexed Pages Checker form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexedPagesChecker(Request $request)
    {
        // If the Google Custom Search API is not enabled
        if (!config('settings.gcs')) {
            abort(404);
        }

        return view('tools.container', ['view' => 'indexed-pages-checker']);
    }

    /**
     * Process the Indexed Pages Checker.
     *
     * @param ProcessIndexedPagesCheckerRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processIndexedPagesChecker(ProcessIndexedPagesCheckerRequest $request)
    {
        // If the Google Custom Search API is not enabled
        if (!config('settings.gcs')) {
            abort(404);
        }

        $httpClient = new HttpClient();

        $result = false;
        try {
            $searchRequest = $httpClient->request('GET', 'https://www.googleapis.com/customsearch/v1?key=' . urlencode(config('settings.gcs_key')) . '&cx=' . config('settings.gcs_id') . '&gl=' . urlencode($request->input('country')) . '&q=' . urlencode('site:' . $request->input('domain')), [
                'http_errors' => false,
                'timeout' => config('settings.request_timeout'),
                'allow_redirects' => [
                    'max'             => 10,
                    'strict'          => true,
                    'referer'         => true,
                    'protocols'       => ['http', 'https']
                ],
                'headers' => [
                    'User-Agent' => config('settings.request_user_agent')
                ]
            ]);

            $result = json_decode($searchRequest->getBody()->getContents(), true);
        } catch (\Exception $e) {}

        return view('tools.container', ['view' => 'indexed-pages-checker', 'domain' => $request->input('domain'), 'country' => $request->input('country'), 'result' => $result]);
    }

    /**
     * Show the Keyword Research form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function keywordResearch(Request $request)
    {
        // If the KeywordsEverywhere API is not enabled
        if (!config('settings.ke')) {
            abort(404);
        }

        return view('tools.container', ['view' => 'keyword-research']);
    }

    /**
     * Process the Keyword Research.
     *
     * @param ProcessKeywordResearchRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processKeywordResearch(ProcessKeywordResearchRequest $request)
    {
        // If the KeywordsEverywhere is not enabled
        if (!config('settings.ke')) {
            abort(404);
        }

        $httpClient = new HttpClient();

        $keywords = preg_split('/\n|\r/', $request->input('keywords'), -1, PREG_SPLIT_NO_EMPTY);

        if (!empty($keywords)) {
            try {
                $keywordRequest = $httpClient->request('POST', 'https://api.keywordseverywhere.com/v1/get_keyword_data', [
                    'http_errors' => false,
                    'timeout' => config('settings.request_timeout'),
                    'allow_redirects' => [
                        'max'             => 10,
                        'strict'          => true,
                        'referer'         => true,
                        'protocols'       => ['http', 'https']
                    ],
                    'headers' => [
                        'User-Agent' => config('settings.request_user_agent'),
                        'Authorization' => 'Bearer ' . config('settings.ke_key')
                    ],
                    'form_params' => [
                        'country' => $request->input('country'),
                        'currency' => $request->input('currency'),
                        'kw' => $keywords
                    ]
                ]);

                $results = json_decode($keywordRequest->getBody()->getContents(), true);
            } catch (\Exception $e) {}
        }

        return view('tools.container', ['view' => 'keyword-research', 'keywords' => $request->input('keywords'), 'country' => $request->input('country'), 'currency' => $request->input('currency'), 'results' => $results]);
    }

    /**
     * Show the Website Status Checker form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function websiteStatusChecker(Request $request)
    {
        return view('tools.container', ['view' => 'website-status-checker']);
    }

    /**
     * Process the Website Status Checker.
     *
     * @param ProcessWebsiteStatusCheckerRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processWebsiteStatusChecker(ProcessWebsiteStatusCheckerRequest $request)
    {
        $domain = str_replace(['http://', 'https://'], '', $request->input('domain'));

        $httpClient = new HttpClient();

        $websiteStatusRequest = false;
        $websiteStatusStats = null;
        try {
            $websiteStatusRequest = $httpClient->request('GET', 'http://' . $domain, [
                'proxy' => [
                    'http' => getRequestProxy(),
                    'https' => getRequestProxy()
                ],
                'http_errors' => false,
                'verify' => false,
                'timeout' => config('settings.request_timeout'),
                'allow_redirects' => [
                    'max'             => 10,
                    'strict'          => true,
                    'referer'         => true,
                    'protocols'       => ['http', 'https']
                ],
                'headers' => [
                    'User-Agent' => config('settings.request_user_agent')
                ],
                'on_stats' => function (TransferStats $stats) use (&$websiteStatusStats) {
                    if ($stats->hasResponse()) {
                        $websiteStatusStats = $stats->getHandlerStats();
                    }
                }
            ]);
        } catch (\Exception $e) {}

        return view('tools.container', ['view' => 'website-status-checker', 'domain' => $domain, 'result' => $websiteStatusRequest, 'stats' => $websiteStatusStats]);
    }

    /**
     * Show the SSL Checker form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sslChecker(Request $request)
    {
        return view('tools.container', ['view' => 'ssl-checker']);
    }

    /**
     * Process the SSL Checker.
     *
     * @param ProcessSslCheckerRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processSslChecker(ProcessSslCheckerRequest $request)
    {
        $domain = str_replace(['http://', 'https://'], '', $request->input('domain'));

        $ssl = false;
        try {
            $ssl = SslCertificate::createForHostName($domain, config('settings.request_timeout'), false);
        } catch (\Exception $e) {}

        return view('tools.container', ['view' => 'ssl-checker', 'domain' => $domain, 'result' => $ssl]);
    }

    /**
     * Show the DNS Lookup form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dnsLookup(Request $request)
    {
        return view('tools.container', ['view' => 'dns-lookup']);
    }

    /**
     * Process the DNS Lookup.
     *
     * @param ProcessDnsLookupRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processDnsLookup(ProcessDnsLookupRequest $request)
    {
        $domain = str_replace(['http://', 'https://'], '', $request->input('domain'));

        try {
            $dnsRecords = dns_get_record($domain, DNS_A + DNS_AAAA + DNS_CNAME + DNS_MX + DNS_TXT + DNS_NS);
        } catch (\Exception $e) {
            $dnsRecords = [];
        }

        return view('tools.container', ['view' => 'dns-lookup', 'domain' => $domain, 'results' => $dnsRecords]);
    }

    /**
     * Show the WHOIS Lookup form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function whoisLookup(Request $request)
    {
        return view('tools.container', ['view' => 'whois-lookup']);
    }

    /**
     * Process the WHOIS Lookup.
     *
     * @param ProcessWhoisLookupRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processWhoisLookup(ProcessWhoisLookupRequest $request)
    {
        $domain = str_replace(['http://', 'https://', 'www.'], '', $request->input('domain'));

        $whoisRecords = false;
        try {
            $whoisRecords = Whois::get()->createWhois()->loadDomainInfo($domain);
        } catch (\Exception $e) {}

        return view('tools.container', ['view' => 'whois-lookup', 'domain' => $domain, 'result' => $whoisRecords]);
    }

    /**
     * Show the IP Lookup form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ipLookup(Request $request)
    {
        return view('tools.container', ['view' => 'ip-lookup']);
    }

    /**
     * Process the IP Lookup.
     *
     * @param ProcessIpLookupRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processIpLookup(ProcessIpLookupRequest $request)
    {
        // Get the user's geolocation
        try {
            $result = (new GeoIP(storage_path('app/geoip/GeoLite2-City.mmdb')))->city($request->input('ip'))->raw;
        } catch (\Exception $e) {
            $result = false;
        }

        return view('tools.container', ['view' => 'ip-lookup', 'content' => $request->input('content'), 'result' => $result]);
    }

    /**
     * Show the Reverse IP Lookup form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reverseIpLookup(Request $request)
    {
        return view('tools.container', ['view' => 'reverse-ip-lookup']);
    }

    /**
     * Process the Reverse IP Lookup.
     *
     * @param ProcessReverseIpLookupRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processReverseIpLookup(ProcessReverseIpLookupRequest $request)
    {
        try {
            $result = gethostbyaddr($request->input('ip'));
        } catch (\Exception $e) {
            $result = false;
        }

        return view('tools.container', ['view' => 'reverse-ip-lookup', 'content' => $request->input('content'), 'result' => $result]);
    }

    /**
     * Show the Domain IP Lookup form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function domainIpLookup(Request $request)
    {
        return view('tools.container', ['view' => 'domain-ip-lookup']);
    }

    /**
     * Process the Domain IP Lookup.
     *
     * @param ProcessDomainIpLookupRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processDomainIpLookup(ProcessDomainIpLookupRequest $request)
    {
        try {
            $ip = gethostbyname($request->input('domain'));

            // Get the IP geolocation
            try {
                $result = (new GeoIP(storage_path('app/geoip/GeoLite2-City.mmdb')))->city($ip)->raw;
            } catch (\Exception $e) {
                $result = false;
            }
        } catch (\Exception $e) {
            $result = false;
        }

        return view('tools.container', ['view' => 'domain-ip-lookup', 'content' => $request->input('content'), 'domain' => $request->input('domain'), 'result' => $result]);
    }

    /**
     * Show the Redirect Checker form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function redirectChecker(Request $request)
    {
        return view('tools.container', ['view' => 'redirect-checker']);
    }

    /**
     * Process the Redirect Checker.
     *
     * @param ProcessRedirectCheckerRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws GuzzleException
     */
    public function processRedirectChecker(ProcessRedirectCheckerRequest $request)
    {
        $httpClient = new HttpClient();
        try {
            $redirectRequestTransferStats = [];
            $httpClient->request('GET', $request->input('url'), [
                'proxy' => [
                    'http' => getRequestProxy(),
                    'https' => getRequestProxy()
                ],
                'timeout' => config('settings.request_timeout'),
                'allow_redirects' => [
                    'max' => 10,
                    'strict' => true,
                    'referer' => true,
                    'protocols' => ['http', 'https'],
                    'track_redirects' => true
                ],
                'headers' => [
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => config('settings.request_user_agent')
                ],
                'on_stats' => function (TransferStats $stats) use (&$redirectRequestTransferStats) {
                    if ($stats->hasResponse()) {
                        $redirectRequestTransferStats[] = $stats->getHandlerStats();
                    }
                }
            ]);
        } catch (\Exception $e) {}

        return view('tools.container', ['view' => 'redirect-checker', 'url' => $request->input('url'), 'results' => $redirectRequestTransferStats]);
    }

    /**
     * Show the IDN Converter form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function idnConverter(Request $request)
    {
        return view('tools.container', ['view' => 'idn-converter']);
    }

    /**
     * Process the IDN Converter Lookup.
     *
     * @param ProcessIdnConverterRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processIdnConverter(ProcessIdnConverterRequest $request)
    {
        if ($request->input('type') == 'punycode') {
            $result = idn_to_ascii($request->input('domain'));
        } else {
            $result = idn_to_utf8($request->input('domain'));
        }

        return view('tools.container', ['view' => 'idn-converter', 'domain' => $request->input('domain'), 'type' => $request->input('type'), 'result' => $result]);
    }

    /**
     * Show the JS Minifier form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function jsMinifier(Request $request)
    {
        return view('tools.container', ['view' => 'js-minifier']);
    }

    /**
     * Process the JS Minifier.
     *
     * @param ProcessJsMinifierRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processJsMinifier(ProcessJsMinifierRequest $request)
    {
        $js = new jslite();

        $content = $request->input('content');
        if ($js->load($request->input('content'))) {
            $js->minify();
            $content = $js->compile();
        }

        return view('tools.container', ['view' => 'js-minifier', 'content' => $content]);
    }

    /**
     * Show the CSS Minifier form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cssMinifier(Request $request)
    {
        return view('tools.container', ['view' => 'css-minifier']);
    }

    /**
     * Process the CSS Minifier.
     *
     * @param ProcessCssMinifierRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processCssMinifier(ProcessCssMinifierRequest $request)
    {
        $css = new cssdoc();

        $content = $request->input('content');
        if ($css->load($request->input('content'))) {
            $css->minify();
            $content = $css->compile();
        }

        return view('tools.container', ['view' => 'css-minifier', 'content' => $content]);
    }

    /**
     * Show the HTML Minifier form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function htmlMinifier(Request $request)
    {
        return view('tools.container', ['view' => 'html-minifier']);
    }

    /**
     * Process the HTML Minifier.
     *
     * @param ProcessHtmlMinifierRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processHtmlMinifier(ProcessHtmlMinifierRequest $request)
    {
        $html = new htmldoc();

        $content = $request->input('content');
        if ($html->load($request->input('content'))) {
            $html->minify();
            $content = $html->save();
        }

        return view('tools.container', ['view' => 'html-minifier', 'content' => $content]);
    }

    /**
     * Show the JSON validator form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function jsonValidator(Request $request)
    {
        return view('tools.container', ['view' => 'json-validator']);
    }

    /**
     * Process the JSON validator.
     *
     * @param ProcessJsonValidatorRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processJsonValidator(ProcessJsonValidatorRequest $request)
    {
        return view('tools.container', ['view' => 'json-validator', 'content' => $request->input('content'), 'result' => json_decode($request->input('content')) ?? null]);
    }

    /**
     * Show the Password Generator form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function passwordGenerator(Request $request)
    {
        return view('tools.container', ['view' => 'password-generator']);
    }

    /**
     * Process the Password Generator.
     *
     * @param ProcessPasswordGeneratorRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processPasswordGenerator(ProcessPasswordGeneratorRequest $request)
    {
        $length = $request->input('length');

        // Character sets
        $characters = [];
        if ($request->input('upper_case')) {
            $characters[] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($request->input('lower_case')) {
            $characters[] = 'abcdefghijklmnopqrstuvwxyz';
        }
        if ($request->input('digits')) {
            $characters[] = '1234567890';
        }
        if ($request->input('symbols')) {
            $characters[] = '!@#$%&*?';
        }

        $all = $password = '';

        foreach ($characters as $character) {
            // Prepend a character from the selected sets
            $password .= $character[array_rand(str_split($character))];

            // Store all the available characters from the selected sets
            $all .= $character;
        }

        // Get an array with all the available characters from the selected sets
        $all = str_split($all);

        // Complete the rest of the password
        for ($i = 0; $i < $length - count($characters); $i++) {
            $password .= $all[array_rand($all)];
        }

        // Shuffle the password characters
        $password = str_shuffle($password);

        return view('tools.container', ['view' => 'password-generator', 'content' => $request->input('content'), 'lowerCase' => (bool)$request->input('lower_case'), 'upperCase' => (bool) $request->input('upper_case'), 'digits' => (bool) $request->input('digits'), 'symbols' => (bool) $request->input('symbols'), 'result' => $password, 'length' => $length]);
    }

    /**
     * Show the QR Generator form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function qrGenerator(Request $request)
    {
        return view('tools.container', ['view' => 'qr-generator']);
    }

    /**
     * Process the QR Generator.
     *
     * @param ProcessQrGeneratorRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processQrGenerator(ProcessQrGeneratorRequest $request)
    {
        return view('tools.container', ['view' => 'qr-generator', 'content' => $request->input('content'), 'size' => $request->input('size'), 'result' => $request->input('content')]);
    }

    /**
     * Show the User-Agent parser form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userAgentParser(Request $request)
    {
        return view('tools.container', ['view' => 'user-agent-parser']);
    }

    /**
     * Process the User-Agent parser.
     *
     * @param ProcessUserAgentParserRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processUserAgentParser(ProcessUserAgentParserRequest $request)
    {
        return view('tools.container', ['view' => 'user-agent-parser', 'content' => $request->input('content'), 'userAgent' => $request->input('user_agent'), 'result' => new UserAgent($request->input('user_agent'))]);
    }

    /**
     * Show the MD5 Generator form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function md5Generator(Request $request)
    {
        return view('tools.container', ['view' => 'md5-generator']);
    }

    /**
     * Process the MD5 Generator.
     *
     * @param ProcessMd5GeneratorRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processMd5Generator(ProcessMd5GeneratorRequest $request)
    {
        return view('tools.container', ['view' => 'md5-generator', 'content' => $request->input('content'), 'result' => md5($request->input('content'))]);
    }

    /**
     * Show the Color Converter form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function colorConverter(Request $request)
    {
        return view('tools.container', ['view' => 'color-converter']);
    }

    /**
     * Process the Color Converter.
     *
     * @param ProcessColorConverterRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processColorConverter(ProcessColorConverterRequest $request)
    {
        $color = null;
        $results = [];

        $colorModels = [
            'hex' => '^#([a-fA-F0-9]){6}$',
            'hexa' => '^#([a-fA-F0-9]){8}$',
            'rgb' => '^rgb\s*?\(\s*?(000|0?\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\s*?,\s*?(000|0?\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\s*?,\s*?(000|0?\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\s*?\)$',
            'rgba' => '^rgba\s*?\(\s*?(000|0?\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\s*?,\s*?(000|0?\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\s*?,\s*?(000|0?\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\s*?,\s*?(0|0\.\d*|1|1.0*)\s*?\)$',
            'hsl' => '^hsl\s*?\(\s*?(000|0?\d{1,2}|[1-2]\d\d|3[0-5]\d|360)\s*?,\s*?(000|100|0?\d{2}|0?0?\d)%\s*?,\s*?(000|100|0?\d{2}|0?0?\d)%\s*?\)$',
            'hsla' => '^hsla\s*?\(\s*?(000|0?\d{1,2}|[1-2]\d\d|3[0-5]\d|360)\s*?,\s*?(000|100|0?\d{2}|0?0?\d)%\s*?,\s*?(000|100|0?\d{2}|0?0?\d)%\s*?,\s*?(0|0\.\d*|1|1.0*)\s*?\)$'
        ];

        foreach ($colorModels as $colorModel => $colorPattern) {
            // If the color matches a color model
            if (preg_match('/' . $colorPattern . '/', $request->input('color'), $x)) {
                // Instantiate the color class
                $class = '\OzdemirBurak\Iris\Color\\' . ucfirst($colorModel);
                $color = new $class($request->input('color'));
            }
        }

        foreach ($colorModels as $colorModel => $colorPattern) {
            // Convert and store the color
            $method = 'to' . ucfirst($colorModel);
            $results[$colorModel] = call_user_func([$color, $method]);
        }

        return view('tools.container', ['view' => 'color-converter', 'color' => $request->input('color'), 'results' => $results]);
    }

    /**
     * Show the UTM Builder form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function utmBuilder(Request $request)
    {
        return view('tools.container', ['view' => 'utm-builder']);
    }

    /**
     * Process the UTM Builder.
     *
     * @param ProcessUtmBuilderRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processUtmBuilder(ProcessUtmBuilderRequest $request)
    {
        // Parse the URL components
        $url = parse_url($request->input('url'));

        // Parse the URL query parameters into variables
        parse_str($url['query'] ?? null, $queryParams);

        // Merge the URL query parameters
        $queryParams = array_merge($queryParams, ['source' => $request->input('source'), 'medium' => $request->input('medium'), 'campaign' => $request->input('campaign'), 'term' => $request->input('term'), 'content' => $request->input('content')]);

        // Rebuild the URL query with the new parameters
        $url['query'] = http_build_query($queryParams);

        return view('tools.container', ['view' => 'utm-builder', 'url' => $request->input('url'), 'source' => $request->input('source'), 'medium' => $request->input('medium'), 'campaign' => $request->input('campaign'), 'term' => $request->input('term'), 'content' => $request->input('content'), 'result' => $url['scheme'] . '://' . $url['host'] . ($url['path'] ?? '/') . '?' . $url['query']]);
    }

    /**
     * Show the URL Parser form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function urlParser(Request $request)
    {
        return view('tools.container', ['view' => 'url-parser']);
    }

    /**
     * Process the URL Parser.
     *
     * @param ProcessUrlParserRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processUrlParser(ProcessUrlParserRequest $request)
    {
        return view('tools.container', ['view' => 'url-parser', 'url' => $request->input('url'), 'results' => parse_url($request->input('url'))]);
    }

    /**
     * Show the UUID generator form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uuidGenerator(Request $request)
    {
        return view('tools.container', ['view' => 'uuid-generator']);
    }

    /**
     * Process the UUID generator.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processUuidGenerator(Request $request)
    {
        return view('tools.container', ['view' => 'uuid-generator']);
    }

    /**
     * Show the Lorem Ipsum Generator form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loremIpsumGenerator(Request $request)
    {
        return view('tools.container', ['view' => 'lorem-ipsum-generator']);
    }

    /**
     * Process the Lorem Ipsum Generator.
     *
     * @param ProcessLoremIpsumGeneratorRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processLoremIpsumGenerator(ProcessLoremIpsumGeneratorRequest $request)
    {
        $method = $request->input('type');

        return view('tools.container', ['view' => 'lorem-ipsum-generator', 'type' => $request->input('type'), 'number' => $request->input('number'), 'results' => Lorem::$method($request->input('number'))]);
    }

    /**
     * Show the Text Cleaner form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function textCleaner(Request $request)
    {
        return view('tools.container', ['view' => 'text-cleaner']);
    }

    /**
     * Process the Text Cleaner.
     *
     * @param ProcessTextCleanerRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processTextCleaner(ProcessTextCleanerRequest $request)
    {
        $result = $request->input('content');

        if ($request->input('html_tags')) {
            $result = strip_tags($result);
        }

        if ($request->input('spaces')) {
            if ($request->input('spaces') == 1) {
                $result = preg_replace('/[[:blank:]]+/u', '', trim($result));
            } elseif ($request->input('spaces') == 2) {
                $result = preg_replace('/[[:blank:]]+/u', ' ', trim($result));
            }
        }

        if ($request->input('line_breaks')) {
            if ($request->input('line_breaks') == 1) {
                $result = preg_replace("/[\r\n]+/u", '', $result);
            } elseif ($request->input('line_breaks') == 2) {
                $result = preg_replace("/[\r\n]+/u", "\n\n", $result);
            }
        }

        return view('tools.container', ['view' => 'text-cleaner', 'content' => $request->input('content'), 'htmlTags' => $request->input('html_tags'), 'spaces' => $request->input('spaces'), 'lineBreaks' => $request->input('line_breaks'), 'result' => $result]);
    }

    /**
     * Show the Word Density Counter form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function wordDensityCounter(Request $request)
    {
        return view('tools.container', ['view' => 'word-density-counter']);
    }

    /**
     * Process the Word Density Counter.
     *
     * @param ProcessWordDensityCounter $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processWordDensityCounter(ProcessWordDensityCounter $request)
    {
        // Get all the available words
        preg_match_all('/\w+/u', mb_strtolower(strip_tags($request->input('content'))), $matches);
        $keywords = $matches[0] ?? [];

        // Count the number of words occurrences
        $results = array_count_values($keywords);

        // Sort the words by value in descending order
        arsort($results);

        return view('tools.container', ['view' => 'word-density-counter', 'content' => $request->input('content'), 'results' => $results, 'total' => count($keywords)]);
    }

    /**
     * Show the Word Counter form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function wordCounter(Request $request)
    {
        return view('tools.container', ['view' => 'word-counter']);
    }

    /**
     * Process the Word Counter.
     *
     * @param ProcessWordCounterRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processWordCounter(ProcessWordCounterRequest $request)
    {
        $wordCount = str_word_count($request->input('content'));
        $letterCount = mb_strlen($request->input('content'));

        return view('tools.container', ['view' => 'word-counter', 'content' => $request->input('content'), 'wordCount' => $wordCount, 'letterCount' => $letterCount]);
    }

    /**
     * Show the Case Converter form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function caseConverter(Request $request)
    {
        return view('tools.container', ['view' => 'case-converter']);
    }

    /**
     * Process the Case Converter.
     *
     * @param ProcessCaseConverterRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processCaseConverter(ProcessCaseConverterRequest $request)
    {
        $method = $request->input('type');

        return view('tools.container', ['view' => 'case-converter', 'content' => $request->input('content'), 'type' => $request->input('type'), 'result' => Str::$method($request->input('content'))]);
    }

    /**
     * Show the Text to Slug Converter form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function textToSlugConverter(Request $request)
    {
        return view('tools.container', ['view' => 'text-to-slug-converter']);
    }

    /**
     * Process the Text to Slug Converter.
     *
     * @param ProcessTextToSlugConverterRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processTextToSlugConverter(ProcessTextToSlugConverterRequest $request)
    {
        return view('tools.container', ['view' => 'text-to-slug-converter', 'content' => $request->input('content'), 'result' => Str::slug($request->input('content'))]);
    }

    /**
     * Show the URL Converter form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function urlConverter(Request $request)
    {
        return view('tools.container', ['view' => 'url-converter']);
    }

    /**
     * Process the URL Converter.
     *
     * @param ProcessUrlConverterRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processUrlConverter(ProcessUrlConverterRequest $request)
    {
        if ($request->input('type') == 'encode') {
            $result = urlencode($request->input('content'));
        } else {
            $result = urldecode($request->input('content'));
        }

        return view('tools.container', ['view' => 'url-converter', 'content' => $request->input('content'), 'type' => $request->input('type'), 'result' => $result]);
    }

    /**
     * Show the Binary Converter form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function binaryConverter(Request $request)
    {
        return view('tools.container', ['view' => 'binary-converter']);
    }

    /**
     * Process the Binary Converter.
     *
     * @param ProcessBinaryConverterRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processBinaryConverter(ProcessBinaryConverterRequest $request)
    {
        $result = false;

        if ($request->input('type') == 'binary') {
            // For every character
            for($i = 0; $i < strlen($request->input('content')); $i++) {
                $prepend = '';

                // Encode the current character into binary code
                $binaryCharacter = decbin(ord($request->input('content')[$i]));

                // Get the number of characters in the binary
                $binaryLength = strlen($binaryCharacter);

                // If the binary code is less than 8 bits
                if ($binaryLength < 8) {
                    // For every missing character (bit)
                    for ($x = 8; $x > $binaryLength; $binaryLength++ ) {
                        // Fill the missing bit
                        $prepend .= '0';
                    }
                }

                // Construct the final binary code
                $result .= $prepend . $binaryCharacter . ' ';
            }

            // Trim the ending space
            $result = rtrim($result);
        } else {
            foreach (explode(' ', $request->input('content')) as $ch) {
                $result .= chr(bindec($ch));
            }
        }

        return view('tools.container', ['view' => 'binary-converter', 'content' => $request->input('content'), 'type' => $request->input('type'), 'result' => $result]);
    }

    /**
     * Show the Base64 Converter form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function base64Converter(Request $request)
    {
        return view('tools.container', ['view' => 'base64-converter']);
    }

    /**
     * Process the Base64 Converter.
     *
     * @param ProcessBase64ConverterRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processBase64Converter(ProcessBase64ConverterRequest $request)
    {
        if ($request->input('type') == 'encode') {
            $result = base64_encode($request->input('content'));
        } else {
            $result = base64_decode($request->input('content'));
        }

        return view('tools.container', ['view' => 'base64-converter', 'content' => $request->input('content'), 'type' => $request->input('type'), 'result' => $result]);
    }

    /**
     * Show the Text Replacer form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function textReplacer(Request $request)
    {
        return view('tools.container', ['view' => 'text-replacer']);
    }

    /**
     * Process the Text Replacer.
     *
     * @param ProcessTextReplacerRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processTextReplacer(ProcessTextReplacerRequest $request)
    {
        return view('tools.container', ['view' => 'text-replacer', 'content' => $request->input('content'), 'find' => $request->input('find'), 'replace' => $request->input('replace'), 'result' => str_replace($request->input('find'), $request->input('replace'), $request->input('content'))]);
    }

    /**
     * Show the Text Reverser form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function textReverser(Request $request)
    {
        return view('tools.container', ['view' => 'text-reverser']);
    }

    /**
     * Process the Text Reverser.
     *
     * @param ProcessTextReverserRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processTextReverser(ProcessTextReverserRequest $request)
    {
        $result = Str::reverse($request->input('content'));

        return view('tools.container', ['view' => 'text-reverser', 'content' => $request->input('content'), 'result' => $result]);
    }

    /**
     * Show the Number Generator form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function numberGenerator(Request $request)
    {
        return view('tools.container', ['view' => 'number-generator']);
    }

    /**
     * Process the Number Generator.
     *
     * @param ProcessNumberGeneratorRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processNumberGenerator(ProcessNumberGeneratorRequest $request)
    {
        return view('tools.container', ['view' => 'number-generator', 'min' => $request->input('min'), 'max' => $request->input('max'), 'result' => mt_rand($request->input('min'), $request->input('max'))]);
    }
}
