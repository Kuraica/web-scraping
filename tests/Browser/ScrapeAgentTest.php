<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Redis;

class ScrapeAgentTest extends DuskTestCase
{
    public function testScrapeAgent()
    {
        $this->browse(function (Browser $browser) {
            $data = [];

            $browser->visit('https://www.realestate.com.au/agent/ted-pye-3233288?campaignType=internal&campaignChannel=in_product&campaignSource=rea&campaignName=sell_enq&campaignPlacement=agent_card&campaignKeyword=agency_marketplace&sourcePage=agency_profile&sourceElement=agent_card')
//                ->waitFor('.styles__AgentName-sc-1ifcqm-9', 15) // Čekanje dok se element ne pojavi
                ->pause(5000) // Dodatna pauza za sigurnost
                ->driver->executeScript('return document.readyState === "complete";')
                ->waitUntilMissing('@loading-indicator', 15)
                ->driver->executeScript("
                    return new Promise((resolve) => {
                        const interval = setInterval(() => {
                            if (window.performance.getEntriesByType('resource').length === 0) {
                                clearInterval(interval);
                                resolve(true);
                            }
                        }, 100);
                    });
                ")
                ->driver->executeScript('window.scrollTo(0, document.body.scrollHeight);'); // Skrol do dna stranice

            $browser->pause(rand(2000, 5000)) // Još jedno nasumično kašnjenje
            ->driver->executeScript('Object.defineProperty(navigator, "webdriver", {get: () => undefined});');


            // Dobijanje HTML sadržaja stranice
            $html = $browser->driver->getPageSource();
            dd($html);
            $data['name'] = $browser->text('.styles__AgentName-sc-1ifcqm-9');
            $data['agency'] = $browser->text('.LinkBase-sc-12oy0hl-0');
            $data['phone'] = $browser->text('.Link__SpanWithMargin-sc-8zfb96-1');
            $data['phone2'] = $browser->attribute('.styles__CallLink-sc-1s798nz-1', 'href');
            dd($data);
            // Store the data in Redis
            Redis::set('scraped_data', json_encode($data));
        });
    }
}

