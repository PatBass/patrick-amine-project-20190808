<?php

namespace App\Tests\FunctionalTests\Front\All;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class GratificationStagiaireTest extends WebTestCase
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
     * Method testGratificationStagiaire
     * @test
     */
    public function testGratificationStagiaire()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/gratification-stagiaire | 
        $this->webDriver->get("$url/calcul/gratification-stagiaire");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Simulateur de calcul de la gratification minimale d'un stagiaire - Calcul - service-public.fr")
		);		

		
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("dateSignatureConvention"))->clear();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("dateSignatureConvention"))->sendKeys("04/07/2016");

		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='L’employeur est-il un organisme public ?'])[1]/following::label[1]"))
		);				
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='L’employeur est-il un organisme public ?'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='L’employeur est-il un organisme public ?'])[1]/following::label[1]"))->click();

        // click | id=heuresPresenceJour | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("heuresPresenceJour"))->click();
        // type | id=heuresPresenceJour | 6
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("heuresPresenceJour"))->clear();
        // type | id=heuresPresenceJour | 6
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("heuresPresenceJour"))->sendKeys("6");		

		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("mois1"))
		);				
        // click | id=mois1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("mois1"))->click();
        // select | id=mois1 | label=Janvier
        $this->getSelect($this->webDriver->findElement(WebDriver\WebDriverBy::id("mois1")))->selectByVisibleText("Janvier");
        // click | id=mois1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("mois1"))->click();
        // click | id=an1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("an1"))->click();
        // select | id=an1 | label=2019
        $this->getSelect($this->webDriver->findElement(WebDriver\WebDriverBy::id("an1")))->selectByVisibleText("2019");
        // click | id=an1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("an1"))->click();
        // click | id=mois2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("mois2"))->click();
        // select | id=mois2 | label=Février
        $this->getSelect($this->webDriver->findElement(WebDriver\WebDriverBy::id("mois2")))->selectByVisibleText("Février");
        // click | id=mois2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("mois2"))->click();
        // click | id=an2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("an2"))->click();
        // select | id=an2 | label=2019
        $this->getSelect($this->webDriver->findElement(WebDriver\WebDriverBy::id("an2")))->selectByVisibleText("2019");
        // click | id=an2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("an2"))->click();
        // click | id=mois3 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("mois3"))->click();
        // select | id=mois3 | label=Mars
        $this->getSelect($this->webDriver->findElement(WebDriver\WebDriverBy::id("mois3")))->selectByVisibleText("Mars");
        // click | id=mois3 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("mois3"))->click();
        // click | id=an3 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("an3"))->click();
        // select | id=an3 | label=2019
        $this->getSelect($this->webDriver->findElement(WebDriver\WebDriverBy::id("an3")))->selectByVisibleText("2019");
        // click | id=an3 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("an3"))->click();
        // click | id=mois4 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("mois4"))->click();
        // select | id=mois4 | label=Avril
        $this->getSelect($this->webDriver->findElement(WebDriver\WebDriverBy::id("mois4")))->selectByVisibleText("Avril");
        // click | id=mois4 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("mois4"))->click();
        // click | id=an4 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("an4"))->click();
        // select | id=an4 | label=2019
        $this->getSelect($this->webDriver->findElement(WebDriver\WebDriverBy::id("an4")))->selectByVisibleText("2019");
        // click | id=an4 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("an4"))->click();
		

		sleep(1);
		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Remettre à 0'])[1]/preceding::p[4]"))
		);					
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Remettre à 0'])[1]/preceding::p[4] | Gratification totale due pour 84 jours (504 heures) : 1 890,00 €
        $this->assertEquals("Gratification totale due pour 84 jours (504 heures) : 1 890,00 €", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Remettre à 0'])[1]/preceding::p[4]"))->getText());
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Remettre à 0'])[1]/preceding::p[3] | Gratification mensuelle lissée sur la totalité de la durée de stage (4 mois) : 472,50 €
        $this->assertEquals("Gratification mensuelle lissée sur la totalité de la durée de stage (4 mois) : 472,50 €", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Remettre à 0'])[1]/preceding::p[3]"))->getText());
    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }
	
    /**
     * @param WebDriver\Remote\RemoteWebElement $element
     *
     * @return WebDriver\WebDriverSelect
     * @throws WebDriver\Exception\UnexpectedTagNameException
     */
    private function getSelect(WebDriver\Remote\RemoteWebElement $element): WebDriver\WebDriverSelect
    {
        return new WebDriver\WebDriverSelect($element);
    }	
}
