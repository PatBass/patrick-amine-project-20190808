<?php

namespace App\Tests\FunctionalTests\Front\All\Headless;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class CautionSolidaireTest extends WebTestCase {
    
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
     * Method testCautionSolidaire
     * @test
     */
    public function testCautionSolidaire()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/ActeCautionnement/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/ActeCautionnement/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Acte de caution solidaire (ou simple) pour une location - Modèle de lettre - service-public.fr")
		);
		
        // click | id=boutt1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("boutt1"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Prenom'))
		);			
        // type | id=Prenom | Prenom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Prenom"))->sendKeys("Prenom");
        // type | id=NomCaution | Nom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("NomCaution"))->sendKeys("Nom");
        // click | id=GenreCaution_1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("GenreCaution_1"))->click();
        // type | id=DateNaissanceCaution | 01/01/2000
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DateNaissanceCaution"))->sendKeys("01/01/2000");
        // type | id=LieuNaissanceCaution | Naissance
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("LieuNaissanceCaution"))->sendKeys("Naissance");
        // type | id=AdresseCaution | Adresse
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseCaution"))->sendKeys("Adresse");
        // type | id=CPCaution | 12300
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CPCaution"))->sendKeys("12300");
        // type | id=CommuneCaution | Commune
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CommuneCaution"))->sendKeys("Commune");
		
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='simple'])[1]/following::label[1]"))
		);		
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='simple'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='simple'])[1]/following::label[1]"))->click();
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='indéterminée'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='indéterminée'])[1]/following::label[1]"))->click();

        // click | id=DureeDetermineeEngagement | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DureeDetermineeEngagement"))->click();
        // type | id=DureeDetermineeEngagement | 3 ans
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DureeDetermineeEngagement"))->sendKeys("3 ans");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("PrenomLocataire"))
		);			
        // click | id=PrenomLocataire | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("PrenomLocataire"))->click();
        // type | id=PrenomLocataire | Locataire
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("PrenomLocataire"))->sendKeys("Locataire");
        // type | id=PrenomLocataire | Prénom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("PrenomLocataire"))->sendKeys("Prénom");
        // type | id=NomLocataire | Nom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("NomLocataire"))->sendKeys("Nom");
        // type | id=PrenomBailleur | BailleurPrenom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("PrenomBailleur"))->sendKeys("BailleurPrenom");
        // type | id=NomBailleur | NomBailleur
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("NomBailleur"))->sendKeys("NomBailleur");
        // click | id=AdresseBailleur | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseBailleur"))->click();
        // type | id=AdresseBailleur | Adresse
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseBailleur"))->sendKeys("Adresse");
        // type | id=CPBailleur | 12300
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CPBailleur"))->sendKeys("12300");
        // type | id=CommuneBailleur | Commune
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CommuneBailleur"))->sendKeys("Commune");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("AdresseLocation"))
		);			
        // click | id=AdresseLocation | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseLocation"))->click();
        // type | id=AdresseLocation | adresse
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseLocation"))->sendKeys("adresse");
        // type | id=CPLocation | 12300
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CPLocation"))->sendKeys("12300");
        // type | id=CommuneLocation | Commune
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CommuneLocation"))->sendKeys("Commune");
        // type | id=MontantLoyerLettres | mille euros
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("MontantLoyerLettres"))->sendKeys("mille euros");
        // type | id=MontantLoyerChiffres | 1000
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("MontantLoyerChiffres"))->sendKeys("1000");
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Payable chaque :'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Payable chaque :'])[1]/following::label[1]"))->click();
        // click | id=DateRevisionJJMM | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DateRevisionJJMM"))->click();
        // type | id=DateRevisionJJMM | 01/08
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DateRevisionJJMM"))->sendKeys("01/08");
        // click | id=listbox-edit-DatePeriodeReferenceIRL | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("listbox-edit-DatePeriodeReferenceIRL"))->click();
        // click | id=itemDatePeriodeReferenceIRL-3 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("itemDatePeriodeReferenceIRL-3"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::name("AnneeReferenceIRL"))
		);	
        // type | name=AnneeReferenceIRL | 2018
        $this->webDriver->findElement(WebDriver\WebDriverBy::name("AnneeReferenceIRL"))->sendKeys("2018");
		
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("LieuActe"))
		);		
        // click | id=LieuActe | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("LieuActe"))->click();
        // type | id=LieuActe | commune
        $this->webDriver->findElement(WebDriver\WebDriverBy::name("LieuActe"))->sendKeys("commune");		
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::name("DeplacerFocus"))
		);	
			
        // assertElementPresent | name=DeplacerFocus | 
        $this->assertEquals(true, $this->webDriver->findElement(WebDriver\WebDriverBy::name("DeplacerFocus"))->isDisplayed() );				
		
		
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Acte de cautionnement'])[1]/following::p[18] | Prenom NOM
        $this->assertEquals("Prenom NOM", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Acte de cautionnement'])[1]/following::p[18]"))->getText());


        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Acte de cautionnement'])[1]/following::p[3] | J'ai pris connaissance du montant du loyer de mille euros euros, soit 1000 euros par mois. Il sera révisé annuellement tous les 01/08 selon la variation de l'indice de référence des loyers publié par l'INSEE au 2e trimestre 2018.
        $this->assertEquals("J'ai pris connaissance du montant du loyer de mille euros euros, soit 1000 euros par mois. Il sera révisé annuellement tous les 01/08 selon la variation de l'indice de référence des loyers publié par l'INSEE au 2e trimestre 2018.", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Acte de cautionnement'])[1]/following::p[3]"))->getText());		
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
