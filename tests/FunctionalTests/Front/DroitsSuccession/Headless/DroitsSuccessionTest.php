<?php

namespace App\Tests\FunctionalTests\Front\DroitsSuccession\Headless;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class DroitsSuccessionTest extends WebTestCase
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
        $options->addArguments(['--headless','--disable-gpu', '--window-size=1200,1100', '--no-sandbox']);

        $desiredCapabilities = WebDriver\Remote\DesiredCapabilities::chrome();
        $desiredCapabilities->setCapability('trustAllSSLCertificates', true);

        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->webDriver = WebDriver\Remote\RemoteWebDriver::create(
                        $urlSelenuim,
                        $desiredCapabilities
        );
    }
	
    /**
     * Method testDroitsSuccession
     * @test
     */
    public function testDroitsSuccession()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/droits-succession/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/droits-succession/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Simulateur de calcul des droits de succession - Vous - service-public.fr")
		);	
		
		
        // click | id=liendeparentetmp | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("liendeparentetmp"))->click();
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('liendeparentetmp_10'))
		);			
        // click | id=itemliendeparentetmp-10 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("liendeparentetmp_10"))->click();
		
		
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('situationHandicap'))
		);				
        // click | id=situationHandicap_1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("situationHandicap_1"))->click();
	
	
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('mutileDeGuerre'))
		);			
        // click | id=mutileDeGuerre_1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("mutileDeGuerre_1"))->click();
		
		

		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('dateOuvertureSuccession'))
		);			
        // click | id=cell1-dateOuvertureSuccession | 
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("dateOuvertureSuccession"))->clear();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("dateOuvertureSuccession"))->sendKeys("01/07/2019");
		
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::name('suivant'))
		);				
        // click | name=suivant | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::name("suivant"))->click();
		
		
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)=concat('victime de guerre ou d', "'", 'acte de terrorisme')])[1]/following::label[3] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)=concat('victime de guerre ou d', \"'\", 'acte de terrorisme')])[1]/following::label[3]"))->click();

		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Le domicile fiscal du défunt était-il en France ?'])[1]/following::label[1]"))
		);	
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Le domicile fiscal du défunt était-il en France ?'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Le domicile fiscal du défunt était-il en France ?'])[1]/following::label[1]"))->click();
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Suivant'])[1]/span[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Suivant'])[1]/span[1]"))->click();
        // click | id=montantActifSuccessoral | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("montantActifSuccessoral"))->click();
        // type | id=montantActifSuccessoral | 1000000
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("montantActifSuccessoral"))->sendKeys("1000000");
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Suivant'])[1]/span[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Suivant'])[1]/span[1]"))->click();

		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("bareme1"))
		);			
        // assertText | id=bareme1 | 55,00
        $this->assertEquals("55,00", $this->webDriver->findElement(WebDriver\WebDriverBy::id("bareme1"))->getText());
        // assertText | id=droitsSuccessionDefinitifs | 545 618,00
        $this->assertEquals("549 123,00", $this->webDriver->findElement(WebDriver\WebDriverBy::id("droitsSuccessionDefinitifs"))->getText());
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
