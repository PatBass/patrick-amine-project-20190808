<?php

namespace App\Tests\FunctionalTests\Front\FormulaireBOAMP\Headless;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class FormulaireBOAMPTest extends WebTestCase
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
     * Method testFormulaireBOAMP
     * @test
     */
    public function testFormulaireBOAMP()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/formulaires/boamp-acheteur/tryIt | 
        $this->webDriver->get("$url/calcul/formulaires/boamp-acheteur/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Choix du formulaire BOAMP - Choix formulaire - Boamp.fr")
		);				
		
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Collectivités territoriales et établissements publics territoriaux'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Collectivités territoriales et établissements publics territoriaux'])[1]/following::label[1]"))->click();
		

		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Type de marché'])[1]/following::label[1]"))
		);				
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Type de marché'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Type de marché'])[1]/following::label[1]"))->click();
	
	
		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='De 25 000 € à 89 999 €'])[3]/following::label[1]"))
		);				
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='De 25 000 € à 89 999 €'])[3]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='De 25 000 € à 89 999 €'])[3]/following::label[1]"))->click();
		
		
		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Avis en cas de transparence ex ante volontaire / Intention de conclure'])[1]/following::label[1]"))
		);			
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Avis en cas de transparence ex ante volontaire / Intention de conclure'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Avis en cas de transparence ex ante volontaire / Intention de conclure'])[1]/following::label[1]"))->click();

		
		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)=concat('(géré dans le forfait \"européen\" ou facturé 1 UP à l', \"'\", 'unité)')])[3]/following::p[1]"))
		);				
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)=concat('(géré dans le forfait "européen" ou facturé 1 UP à l', "'", 'unité)')])[3]/following::p[1] | FNS - F3 formulaire Résultat de marché
        $this->assertEquals("FNS - F3 formulaire Résultat de marché", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)=concat('(géré dans le forfait \"européen\" ou facturé 1 UP à l', \"'\", 'unité)')])[3]/following::p[1]"))->getText());
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)=concat('(géré dans le forfait "national" ou facturé à l', "'", 'unité 8 UP)')])[4]/following::p[1] | DIVERS - G2 formulaire Résultat de marché (*)
        $this->assertTrue((bool)preg_match('/^DIVERS - G2 formulaire Résultat de marché \([\s\S]*\)$/',$this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)=concat('(géré dans le forfait \"national\" ou facturé à l', \"'\", 'unité 8 UP)')])[4]/following::p[1]"))->getText()));
    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }
}
