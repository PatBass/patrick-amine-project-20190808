<?php

namespace App\Tests\FunctionalTests\Front\All;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class CarteGriseVerificationResultatCas2Test extends WebTestCase {

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
			(new Dotenv(true))->load(__DIR__.'/../../../../.env');
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
        $options->addArguments(['--disable-gpu', '--window-size=1200,1100', '--no-sandbox']);

        $desiredCapabilities = WebDriver\Remote\DesiredCapabilities::chrome();
        $desiredCapabilities->setCapability('trustAllSSLCertificates', true);

        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->webDriver = WebDriver\Remote\RemoteWebDriver::create(
                        $urlSelenuim,
                        $desiredCapabilities
        );
    }


    /**
     * Method testVerificationResultatCas2
     * @test
     */
    public function testVerificationResultatCas2() {
		
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
 
        // open | $url/calcul/cout-certificat-immatriculation | 
        $this->webDriver->get("$url/calcul/cout-certificat-immatriculation");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Simulateur du coût du certificat d'immatriculation - Données - service-public.fr")
		);				

        // click | id=combobox-demarche | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("combobox-demarche"))->click();		
		
		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("itemdemarche-2"))
		);					
        // click | id=itemdemarche-2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("itemdemarche-2"))->click();

		
		sleep(2);
		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Précisez si le véhicule a été'])[1]/following::label[1]"))
		);			
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Précisez si le véhicule a été'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Précisez si le véhicule a été'])[1]/following::label[1]"))->click();


		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("listbox-edit-typeVehicule"))
		);			
        // click | id=listbox-edit-typeVehicule | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("combobox-typeVehicule"))->click();		
		
		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("itemtypeVehicule-2"))
		);	
        // click | id=itemtypeVehicule-2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("itemtypeVehicule-2"))->click();	
		


		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('puissanceAdministrative'))
		);			
		
        // type | id=puissanceAdministrative | 12
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("puissanceAdministrative"))->sendKeys("12");

		
		sleep(1);
		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('listbox-edit-energie'))
		);			
        // click | id=itemenergie-2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("listbox-edit-energie"))->click();
        // click | id=itemenergie-2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("itemenergie-3"))->click();		
		

		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("tauxCO2"))
		);			

        // type | id=tauxCO2 | 100
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("tauxCO2"))->sendKeys("100");
				
		
		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('listbox-edit-departement'))
		);			
        // click | id=itemenergie-2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("listbox-edit-departement"))->click();		

		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('itemdepartement-77'))
		);	
        // click | id=itemdepartement-77 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("itemdepartement-77"))->click();


		
        // click | name=calculer | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::name("calculer"))->click();

        // assertText | id=taxesAPayerY6 | 283,760
        $this->assertEquals("560,76", $this->webDriver->findElement(WebDriver\WebDriverBy::id("taxesAPayerY6"))->getText());			
    }

    /**
     * Close the current window.
     */
    public function tearDown() {
        $this->webDriver->close();
    }

}
