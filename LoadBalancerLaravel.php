<?php

namespace App\Library;

use Illuminate\Support\Facades\Cache;

final class LoadBalancerLaravel
{
    public function getNextUrl() {
        $url = $this->selectUrl();
        return $url;
    }

    private function selectUrl() {
        $loads = $this->getLoads();

        $minLoad = min($loads);
        $minLoadUrls = array_keys($loads, $minLoad);
        $selectedUrl = $minLoadUrls[array_rand($minLoadUrls)];
		
		$this->updateLoad($selectedUrl);
		
        return $selectedUrl;
    }

    private function getLoads() {
        return Cache::get('load_balancer_loads', []);
    }

    private function setLoads($loads) {
        Cache::put('load_balancer_loads', $loads);
    }

    public function releaseLoad($url) {
        $loads = $this->getLoads();
        if (isset($loads[$url]) && $loads[$url] > 0) {
            $loads[$url]--;
            $this->setLoads($loads);
        }
    }

    public function updateLoad($url) {
        $loads = $this->getLoads();
        if (isset($loads[$url])) {
            $loads[$url]++;
            $this->setLoads($loads);
        }
    }
}

/*

// Example usage
$urls = ['http://example1.com', 'http://example2.com', 'http://example3.com'];

// Initialize loads if not already cached
if (!Cache::has('load_balancer_loads')) {
    $initialLoads = array_fill_keys($urls, 0);
    Cache::put('load_balancer_loads', $initialLoads);
}

$loadBalancer = new LoadBalancer($urls);

// Simulating tasks completion
$completedUrl = 'http://example2.com'; // Assuming a task was completed on this URL
$loadBalancer->releaseLoad($completedUrl);

// Simulating new tasks
$newTaskUrl = $loadBalancer->getNextUrl(); // Get the next URL to handle a new task
echo "Assigning task to: $newTaskUrl";
$loadBalancer->updateLoad($newTaskUrl); // Update the load for the assigned URL

*/
