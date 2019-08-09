<?php

namespace App\Tests\FunctionalTests\Front\All\Headless;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class LettreCongeParentPublicTest extends WebTestCase
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
     * Method testLettreCongeParentPublic
     * @test
     */
    public function testLettreCongeParentPublic()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/CongeParentalFonctionPublique/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/CongeParentalFonctionPublique/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Demande initiale de congé parental dans la fonction publique - Modèle de lettre - service-public.fr")
		);		
		
        // click | id=boutt1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("boutt1"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Prenom'))
		);					
        // type | id=Prenom | Prenom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Prenom"))->sendKeys("Prenom");
        // type | id=name | Nom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("name"))->sendKeys("Nom");
        // type | id=AdresseExp | Adresse
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseExp"))->sendKeys("Adresse");
        // type | id=CodePostalExpediteur | 12300
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CodePostalExpediteur"))->sendKeys("12300");
        // type | id=VilleExp | Commune
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleExp"))->sendKeys("Commune");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('NomSocieteDest'))
		);					
        // click | id=NomSocieteDest | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("NomSocieteDest"))->click();
        // type | id=NomSocieteDest | Employeur
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("NomSocieteDest"))->sendKeys("Employeur");
        // type | id=AdresseDestinataire | Adresse
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseDestinataire"))->sendKeys("Adresse");
        // type | id=CodePostalDestinataire | 12300
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CodePostalDestinataire"))->sendKeys("12300");
        // type | id=VilleDestinataire | Commune
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleDestinataire"))->sendKeys("Commune");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('VilleLettre'))
		);					
        // click | id=VilleLettre | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleLettre"))->click();
        // type | id=VilleLettre | Commune
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleLettre"))->sendKeys("Commune");
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Type de remise de la lettre'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Type de remise de la lettre'])[1]/following::label[1]"))->click();
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Lettre recommandée avec accusé de réception'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Lettre recommandée avec accusé de réception'])[1]/following::label[1]"))->click();
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Genre du destinataire'])[1]/following::label[1] | 

		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Genre du destinataire'])[1]/following::label[1]"))
		);	
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Genre du destinataire'])[1]/following::label[1]"))->click();
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='*'])[15]/following::span[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='*'])[15]/following::span[1]"))->click();

		// click | id=cell4-DateDebutConge | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DateDebutConge"))->clear();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("DateDebutConge"))->sendKeys("01/07/2019");	
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::name("DeplacerFocus"))
		);				
        // assertElementPresent | name=DeplacerFocus | 
        $this->assertEquals(true, $this->webDriver->findElement(WebDriver\WebDriverBy::name("DeplacerFocus"))->isDisplayed() );		
		
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='afficherbouttonsuivant'])[1]/following::p[2] | Prenom NOM
        $this->assertEquals("Prenom NOM", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='afficherbouttonsuivant'])[1]/following::p[2]"))->getText());


		
				
		$text = $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Prenom NOM'])[2]/preceding::p[10]"))->getText();
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Prenom NOM'])[2]/preceding::p[10] | Remise en mains propres contre décharge
        $this->assertEquals("Remise en mains propres contre décharge", $text);

    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }
}
