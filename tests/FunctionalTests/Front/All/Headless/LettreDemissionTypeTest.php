<?php

namespace App\Tests\FunctionalTests\Front\All\Headless;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class LettreDemissionTest extends WebTestCase
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
     * Method testLettreDemission
     * @test
     */
    public function testLettreDemission()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/Demission/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/Demission/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Lettre de démission du salarié - Modèle de lettre - service-public.fr")
		);	
		
        // click | id=boutt1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("boutt1"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Prenom'))
		);					
        // type | id=Prenom | Prénom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Prenom"))->sendKeys("Prénom");
        // type | id=name | Nom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("name"))->sendKeys("Nom");
        // type | id=AdresseExp | Adresse
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseExp"))->sendKeys("Adresse");
        // type | id=CodePostalExpediteur | 85000
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CodePostalExpediteur"))->sendKeys("85000");
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
        // click | id=GenreDestinataire_1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("GenreDestinataire_1"))->click();
        // click | id=Nometfonctiondudestinataire | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Nometfonctiondudestinataire"))->click();
        // click | id=AdresseDestinataire | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseDestinataire"))->click();
        // type | id=AdresseDestinataire | adresse
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseDestinataire"))->sendKeys("adresse");
        // click | id=CodePostalDestinataire | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CodePostalDestinataire"))->click();
        // type | id=CodePostalDestinataire | 12350
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CodePostalDestinataire"))->sendKeys("12350");
        // click | id=VilleDestinataire | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleDestinataire"))->click();
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
        // type | id=DateJour | 01/07/2019
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("DateJour"))->clear();
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DateJour"))->sendKeys("01/07/2019");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('FonctionOccupee'))
		);			
		// click | id=DureePreavis | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DureePreavis"))->click();
        // type | id=DureePreavis | 1 mois
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DureePreavis"))->sendKeys("1 mois");
		
		
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("DateDebutContrat"))->clear();
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DateDebutContrat"))->sendKeys("01/07/2016");		
		
        // click | id=FonctionOccupee | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("FonctionOccupee"))->click();
        // type | id=FonctionOccupee | Fonction
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("FonctionOccupee"))->sendKeys("Fonction");
	
 


        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Souhaitez-vous demander à ne pas effectuer de préavis ?'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Souhaitez-vous demander à ne pas effectuer de préavis ?'])[1]/following::label[1]"))->click();
 
	
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Durée du préavis régie par :'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Durée du préavis régie par :'])[1]/following::label[1]"))->click();

		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::name("DeplacerFocus"))
		);				
        // assertElementPresent | name=DeplacerFocus | 
        $this->assertEquals(true, $this->webDriver->findElement(WebDriver\WebDriverBy::name("DeplacerFocus"))->isDisplayed() );		
		
		// assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='afficherboutonsuivant'])[1]/following::p[2] | Prénom NOM
        $this->assertEquals("Prénom NOM", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='afficherboutonsuivant'])[1]/following::p[2]"))->getText());
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Prénom NOM'])[2]/preceding::div[1] | À Commune, le 1 juillet 2019
        $this->assertEquals("À Commune, le 1 juillet 2019", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Prénom NOM'])[2]/preceding::div[1]"))->getText());

    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }

}
