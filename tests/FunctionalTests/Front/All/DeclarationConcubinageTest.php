<?php

namespace App\Tests\FunctionalTests\Front\All;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class DeclarationConcubinageTest extends WebTestCase
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
     * Method testDeclarationConcubinage
     * @test
     */
    public function testDeclarationConcubinage()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/Concubinage/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/Concubinage/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Déclaration de concubinage - Modèle de lettre - service-public.fr")
		);		
		
        // click | id=boutt1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("boutt1"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Prenom'))
		);				
        // type | id=Prenom | Prenom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Prenom"))->sendKeys("Prenom");
        // type | id=Nom | Nom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Nom"))->sendKeys("Nom");

        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Genre'])[2]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Genre'])[2]/following::label[1]"))->click();
	
        // click | id=LieuDeNaissance1 | 
        //$this->webDriver->findElement(WebDriver\WebDriverBy::id("LieuDeNaissance1"))->click();
		
        // type | id=LieuDeNaissance1 | Naissance
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("LieuDeNaissance1"))->sendKeys("Naissance");		
		
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DateDeNaissance1"))->clear();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("DateDeNaissance1"))->sendKeys("04/07/2001");			
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Prenom2'))
		);			
        // type | id=Prenom2 | Prenom2
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Prenom2"))->sendKeys("Prenom2");
        // type | id=Nom2 | Nom2
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Nom2"))->sendKeys("Nom2");
		

        // type | id=DateDeNaissance2 | 12/02/2002
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DateDeNaissance2"))->sendKeys("12/02/2002");
        // click | id=LieuDeNaissance2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("LieuDeNaissance2"))->click();
        // type | id=LieuDeNaissance2 | Naissance
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("LieuDeNaissance2"))->sendKeys("Naissance");
		
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Madame'])[2]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Madame'])[2]/following::label[1]"))->click();		
		
		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Date_Debut_Concubinage'))
		);		

        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Date_Debut_Concubinage"))->clear();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("Date_Debut_Concubinage"))->sendKeys("17/07/2019");	
		
		
		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Ville'))
		);				
        // type | id=Ville | Ville
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("Ville"))->click();
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Ville"))->sendKeys("Ville");


        // type | id=Adresse | Adresse
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Adresse"))->sendKeys("Adresse");
        // type | id=CodePostal | 12300
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CodePostal"))->sendKeys("12300");
	
		
		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Commune_Declaration'))
		);			
        // type | id=Commune_Declaration | Commune
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Commune_Declaration"))->sendKeys("Commune");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::name("DeplacerFocus"))
		);			
        // assertElementPresent | name=DeplacerFocus | 
        $this->assertEquals(true, $this->webDriver->findElement(WebDriver\WebDriverBy::name("DeplacerFocus"))->isDisplayed() );	
		
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Prenom NOM'])[1]/following::div[1] | Prenom2 NOM2
        $this->assertEquals("Prenom2 NOM2", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Prenom NOM'])[1]/following::div[1]"))->getText());
	
    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }

}
