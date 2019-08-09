<?php

namespace App\Tests\FunctionalTests\Front\All;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class DemissionAssistanteMatTest extends WebTestCase {

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
     * Method testDemissionAssistanteMat
     * @test
     */
    public function testDemissionAssistanteMat()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/DemissionAssistanteMat/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/DemissionAssistanteMat/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Lettre de démission de l'assistante maternelle - Modèle de lettre - service-public.fr")
		);		
		
        // click | id=boutt1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("boutt1"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Prenom'))
		);			
        // click | id=Prenom | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Prenom"))->click();		
        // type | id=Prenom | Prenoms
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Prenom"))->sendKeys("Prenoms");
        // click | id=name | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("name"))->click();			
        // type | id=name | Nom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("name"))->sendKeys("Nom");

        // click | id=AdresseExp | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseExp"))->click();			
        // type | id=AdresseExp | Adress
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseExp"))->sendKeys("Adress");
        // click | id=CodePostalExpediteur | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CodePostalExpediteur"))->click();		
        // type | id=CodePostalExpediteur | 75011
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CodePostalExpediteur"))->sendKeys("75011");
        // click | id=VilleExp | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleExp"))->click();			
        // type | id=VilleExp | Paris
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleExp"))->sendKeys("Paris");
		
        // click | id=GenreExp_1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Genre'])[1]/following::label[1]"))->click();		
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('NomDestEmployeur1'))
		);				
        // type | id=NomDestEmployeur1 | Employeur
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("NomDestEmployeur1"))->sendKeys("Employeur");
		
        // click | id=GenreDestinataireEmployeur1_1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("GenreDestinataireEmployeur1_1"))->click();
        // type | id=AdresseDestinataire | AdresseEmployeur
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseDestinataire"))->sendKeys("AdresseEmployeur");
        // type | id=CodePostalDestinataire | 75012
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CodePostalDestinataire"))->sendKeys("75012");
        // type | id=VilleDestinataire | PAris
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleDestinataire"))->sendKeys("PAris");


		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('VilleLettre'))
		);			

		
        // type | id=VilleLettre | Paris
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleLettre"))->sendKeys("Paris");
		
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DateJour"))->clear();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("DateJour"))->sendKeys("01/07/2019");
			

		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('DateDebutContrat'))
		);				
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DateDebutContrat"))->clear();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("DateDebutContrat"))->sendKeys("02/07/2019");
		
        // click | id=PreavisRegiParContratConvention_1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("PreavisRegiParContratConvention_1"))->click();	
		
 		$this->webDriver->findElement(WebDriver\WebDriverBy::id("DispensePreavis_2"))->click();				
 
        // click | id=DureePreavis | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DureePreavis"))->click();		 
        // type | id=DureePreavis | 1 mois
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DureePreavis"))->sendKeys("1 mois");

		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::name("DeplacerFocus"))
		);	
        // assertElementPresent | name=DeplacerFocus | 
        $this->assertEquals(true, $this->webDriver->findElement(WebDriver\WebDriverBy::name("DeplacerFocus"))->isDisplayed() );		
		
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='À Paris, le 1 juillet 2019'])[1]/following::p[5] | Cependant, et par dérogation, je sollicite la possibilité de ne pas effectuer ce préavis et, par conséquent, de quitter mon emploi dès confirmation de votre autorisation, mettant ainsi fin à mon contrat de travail.
        $this->assertEquals("Cependant, et par dérogation, je sollicite la possibilité de ne pas effectuer ce préavis et, par conséquent, de quitter mon emploi à la date de la réception de ma lettre de démission, mettant ainsi fin à mon contrat de travail.", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='À Paris, le 1 juillet 2019'])[1]/following::p[5]"))->getText());

        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='afficherboutonsuivant'])[1]/following::p[2] | Prenoms NOM
        $this->assertEquals("Prenoms NOM", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='afficherboutonsuivant'])[1]/following::p[2]"))->getText());
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Prenoms NOM'])[2]/preceding::div[1] | À Paris, le 1 juillet 2019
        $this->assertEquals("À Paris, le 1 juillet 2019", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Prenoms NOM'])[2]/preceding::div[1]"))->getText());
    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }

}
