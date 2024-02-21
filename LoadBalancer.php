<?php 

class LoadBalancer {
    private $urls = [];
    private $loads = [];

    public function __construct(array $urls) {
        $this->urls = $urls;
        $this->initLoads();
    }

    private function initLoads() {
        foreach ($this->urls as $url) {
            $this->loads[$url] = 0;
        }
    }

    public function getNextUrl() {
        $url = $this->selectUrl();
        return $url;
    }

    private function selectUrl() {
        $minLoad = min($this->loads);
        $minLoadUrls = array_keys($this->loads, $minLoad);
        $selectedUrl = $minLoadUrls[array_rand($minLoadUrls)];
        return $selectedUrl;
    }

    public function releaseLoad($url) {
        if (isset($this->loads[$url]) && $this->loads[$url] > 0) {
            $this->loads[$url]--;
        }
    }

    public function updateLoad($url) {
        if (isset($this->loads[$url])) {
            $this->loads[$url]++;
        }
    }
}

/*
// Example usage
$urls = ['http://example1.com', 'http://example2.com', 'http://example3.com'];
$loadBalancer = new LoadBalancer($urls);

// Simulating tasks completion
$completedUrl = 'http://example2.com'; // Assuming a task was completed on this URL
$loadBalancer->releaseLoad($completedUrl);

// Simulating new tasks
$newTaskUrl = $loadBalancer->getNextUrl(); // Get the next URL to handle a new task
echo "Assigning task to: $newTaskUrl";
$loadBalancer->updateLoad($newTaskUrl); // Update the load for the assigned URL

*/
