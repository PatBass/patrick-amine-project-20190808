<?php

namespace App\Tests\FunctionalTests\Front\All;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class InfractionsRoutieresTest extends WebTestCase
{
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
     * Method testInfractionsRoutieres
     * @test
     */
    public function testInfractionsRoutieres()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/Infraction/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/Infraction/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Que risque-t-on en cas d'infraction routière ? - etape1 - service-public.fr")
		);		

		
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)=concat('Sélectionnez une catégorie d', "'", 'infraction')])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)=concat('Sélectionnez une catégorie d', \"'\", 'infraction')])[1]/following::label[1]"))->click();



        // click | id=AutocompletionListbox-resultat_donnee_alcool | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AutocompletionListbox-resultat_donnee_alcool"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("AutocompletionListbox-resultat_donnee_alcool-suggestions-suggestion-4"))
		);				
        // click | id=AutocompletionListbox-resultat_donnee_alcool-suggestions-suggestion-4 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AutocompletionListbox-resultat_donnee_alcool-suggestions-suggestion-4"))->click();

		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("Point1"))
		);				
        // assertText | id=Point1 | 6 points
        $this->assertEquals("6 points", $this->webDriver->findElement(WebDriver\WebDriverBy::id("Point1"))->getText());

        // assertText | id=Delit1 | 4 500 €
        $this->assertEquals("4 500 €", $this->webDriver->findElement(WebDriver\WebDriverBy::id("Delit1"))->getText());

        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)=concat('Conduite sous l', "'", 'emprise d', "'", 'alcool ou de drogue')])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)=concat('Conduite sous l', \"'\", 'emprise d', \"'\", 'alcool ou de drogue')])[1]/following::label[1]"))->click();
        // click | id=AutocompletionListbox-resultat_donnee_vitesse | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AutocompletionListbox-resultat_donnee_vitesse"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("AutocompletionListbox-resultat_donnee_vitesse-suggestions-suggestion-3"))
		);					
        // click | id=AutocompletionListbox-resultat_donnee_vitesse-suggestions-suggestion-3 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AutocompletionListbox-resultat_donnee_vitesse-suggestions-suggestion-3"))->click();

		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("Point2-container"))
		);	
        // assertText | id=Point2-container | Un retrait sur votre permis de2 points
        $this->assertEquals("Un retrait sur votre permis de\n2 points", $this->webDriver->findElement(WebDriver\WebDriverBy::id("Point2-container"))->getText());
    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }
}
