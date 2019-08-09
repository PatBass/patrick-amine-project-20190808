<?php

namespace App\Tests\FunctionalTests\Front\All\Headless;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class LicenceSportiveAffichageTableauEtResultatTest extends WebTestCase {

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
     * Method testAffichageTableauEtResultat
     * @test
     */
    public function testAffichageTableauEtResultat() {
		
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/certificatMedical | 
        $this->webDriver->get("$url/calcul/certificatMedical");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("SIMULATEUR : FAUT-IL UN CERTIFICAT MÉDICAL POUR OBTENIR UNE LICENCE SPORTIVE ? - Questionnaire - service-public.fr")
		);		

        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='disciplines sportives à contraintes particulières'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='disciplines sportives à contraintes particulières'])[1]/following::label[1]"))->click();

        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Non'])[2]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Non'])[2]/following::label[1]"))->click();

        // click | id=lerenouvellementplusde2fois_1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("lerenouvellementplusde2fois_1"))->click();

        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Oui'])[7]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Oui'])[7]/following::label[1]"))->click();

        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Oui'])[8]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Oui'])[8]/following::label[1]"))->click();

        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Oui'])[9]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Oui'])[9]/following::label[1]"))->click();

        // click | id=ReponseGrilleouinon4_2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("ReponseGrilleouinon4_2"))->click();

        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Oui'])[11]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Oui'])[11]/following::label[1]"))->click();

        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Oui'])[12]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Oui'])[12]/following::label[1]"))->click();

        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Oui'])[13]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Oui'])[13]/following::label[1]"))->click();

        // click | id=ReponseQuestion8_2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("ReponseQuestion8_2"))->click();

        // click | id=ReponseQuestion9Aujourd_2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("ReponseQuestion9Aujourd_2"))->click();

		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='oui'])[2]/following::p[1]"))
		);		
		$text = "Vous avez répondu non à toutes les questions, vous n'avez pas de certificat médical à fournir. Simplement attestez, selon les modalités prévues par votre fédération, avoir répondu non à toutes les questions lors de la demande de renouvellement de la licence."	;
        // xpath=(.//*[normalize-space(text()) and normalize-space(.)='oui'])[2]/following::p[1] | Vous avez répondu 
        $this->assertEquals($text, $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='oui'])[2]/following::p[1]"))->getText() );

        // click | id=ReponseQuestion9Aujourd_1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("ReponseQuestion9Aujourd_1"))->click();

		// Attendre jusqu'à l'affichage du bloc suivant // attendre au max 10s .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Non'])[15]/following::p[1]"))
		);		
		$text = "Vous avez répondu oui à une ou plusieurs questions, vous devez fournir un certificat médical de non contre-indication à la pratique du sport ou de la discipline concernée, datant de moins d'un an à la date de la demande de la licence. Consultez votre médecin."	;
        //xpath=(.//*[normalize-space(text()) and normalize-space(.)='Non'])[15]/following::p[1] | Vous avez répondu 
        $this->assertEquals($text, $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Non'])[15]/following::p[1]"))->getText() );

    }

    /**
     * Close the current window.
     */
    public function tearDown() {
        $this->webDriver->close();
    }

}
