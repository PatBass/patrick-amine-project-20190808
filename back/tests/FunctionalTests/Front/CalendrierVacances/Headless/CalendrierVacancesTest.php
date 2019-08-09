<?php

namespace App\Tests\FunctionalTests\Front\CalendrierVacances\Headless;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class CalendrierVacancesTest extends WebTestCase {
    
    /**
     * @var WebDriver\Remote\RemoteWebDriver
     */
    private $webDriver;
	
    /**
     * get Parameters from .env file
     */	
	private function getEnvParamValue($param) {
		if (!isset($_SERVER['APP_ENV'])) {
			if (!class_exists(Dotenv::class)) {
				throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
			}
			(new Dotenv(true))->load(__DIR__.'/../../../../../.env');
		}	
		if (isset($_ENV[$param]))		
			return $_ENV[$param];
		else
			throw new \RuntimeException("$param environment variable is not defined .");	
	}

    /**
     * init webdriver
     */
    public function setUp() {
		
		$urlSelenuim = $this->getEnvParamValue('SELENIUM_URL');
		
        $options = new ChromeOptions();
        $options->addArguments(['--headless','--disable-gpu', '--window-size=1200,4000', '--no-sandbox']);

        $desiredCapabilities = WebDriver\Remote\DesiredCapabilities::chrome();
        $desiredCapabilities->setCapability('trustAllSSLCertificates', true);

        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->webDriver = WebDriver\Remote\RemoteWebDriver::create(
                        $urlSelenuim,
                        $desiredCapabilities
        );
    }
	
    /**
     * Method testCalendrierVacances
     * @test
     */
    public function testCalendrierVacances()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/Dates_Vacances_Scolaires/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/Dates_Vacances_Scolaires/particuliers/tryIt");
		
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Calendrier des vacances scolaires - Dates des vacances - service-public.fr")
		);	

		
        // click | id=AutocompletionListbox-Departement | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AutocompletionListbox-Departement"))->click();
		
        // type | id=AutocompletionListbox-Departement | 92 - Hauts-de-Seine
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AutocompletionListbox-Departement"))->sendKeys("92 - Hauts-de-Seine");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('AutocompletionListbox-Departement-suggestions-suggestion-1'))
		);			
        // click | id=AutocompletionListbox-Departement-suggestions-suggestion-1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AutocompletionListbox-Departement-suggestions-suggestion-1"))->click();
		
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)=concat('Choisissez l', "'", 'année scolaire')])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)=concat('Choisissez l', \"'\", 'année scolaire')])[1]/following::label[1]"))->click();

		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Debut1'))
		);		
        // assertText | id=Debut1 | samedi 20 octobre 2018
        $this->assertEquals("samedi 20 octobre 2018", $this->webDriver->findElement(WebDriver\WebDriverBy::id("Debut1"))->getText());
		
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Fin1'))
		);			
        // assertText | id=Fin1 | lundi 5 novembre 2018
        $this->assertEquals("lundi 5 novembre 2018", $this->webDriver->findElement(WebDriver\WebDriverBy::id("Fin1"))->getText());
    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }

}
