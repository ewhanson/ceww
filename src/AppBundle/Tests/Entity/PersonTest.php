<?php


namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Book;
use AppBundle\Entity\Compilation;
use AppBundle\Entity\Contribution;
use AppBundle\Entity\Periodical;
use AppBundle\Entity\Person;
use AppBundle\Entity\Publication;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class PersonTest extends BaseTestCase {

        /**
     * @dataProvider SetBirthDateData
     */ 
    public function testSetBirthDate($testDate) {
        
        $person = new Person();
        
        $person->setBirthDate($testDate);
        //compare string or values, not whole objects
        $this->AssertEquals($testDate, $person->getBirthDate()->getValue());
        
    }
    // dataProvider function name should not begin with 'test'
        public function SetBirthDateData() {
            
            return array(
                
                [1800],
                ["1800"],
                ["c1800"], 
                [-1800],
            
                ["1800-"],
                ["c1800-"],
            
                ["-1800"],
                ["-c1800"],
            
                ["1800-1801"],
                ["c1800-1801"],
                ["1800-c1801"],
                ["c1800-c1801"],
            
                
                );
          
        }
        
        /**
        * @dataProvider SetDeathDateData
        */ 
        public function testSetDeathDate($testDate) {
        
        $person = new Person();
        
        $person->setDeathDate($testDate);
        //compare string or values, not whole objects
        $this->AssertEquals($testDate, $person->getDeathDate()->getValue());
        
    }
        // dataProvider function name should not begin with 'test'
        public function SetDeathDateData() {
            
            return array(
                
                [1800],
                ["1800"],
                ["c1800"],
                [-1800],
            
                ["1800-"],
                ["c1800-"],
            
                ["-1800"],
                ["-c1800"],
            
                ["1800-1801"],
                ["c1800-1801"],
                ["1800-c1801"],
                ["c1800-c1801"],
          
        );
}
    
    
    public function testGetContributions() {
        $person = new Person();
        
        foreach(array(1,2,3) as $n) {
            $book = new Book();
            $book->setTitle("Book {$n}");
            $contribution = new Contribution();
            $contribution->setPerson($person);
            $contribution->setPublication($book);
            $person->addContribution($contribution);
        }
        foreach(array(4,5) as $n) {
            $compilation = new Compilation();
            $compilation->setTitle("Compilation {$n}");
            $contribution = new Contribution();
            $contribution->setPerson($person);
            $contribution->setPublication($compilation);
            $person->addContribution($contribution);
        }
        
        foreach(array(6,7) as $n) {
            $periodical = new Periodical();
            $periodical->setTitle("Publication {$n}");
            $contribution = new Contribution();
            $contribution->setPerson($person);
            $contribution->setPublication($periodical);
            $person->addContribution($contribution);
            
        }
        $this->assertEquals(3, count($person->getContributions(Publication::BOOK)));
        $this->assertEquals(2, count($person->getContributions(Publication::COMPILATION)));
        $this->assertEquals(2, count($person->getContributions(Publication::PERIODICAL)));
    }

    public function testAddUrlLink() {
        $person = new Person();
        $urlLink = 'http://www.example.com';

        $person->addUrlLink($urlLink);
        
        $this->assertEquals(1, count($person->getUrlLinks()));
    }

    public function testRemoveUrlLink() {
        $person = new Person();
        $urlLink = 'http://www.example.com';

        $person->addUrlLink($urlLink);
        $person->removeUrlLink($urlLink);

        $this->assertEquals(0, count($person->getUrlLinks()));
    }
    
    public function testGetUrlLinks() {
        $person = new Person();
        $urlLinks = ['http://www.example.com', 'http://www.sfu.ca'];
        
        $person->setUrlLinks($urlLinks);

        $this->assertEquals(2, count($person->getUrlLinks()));
        $this->assertContains('http://www.example.com', $person->getUrlLinks());
    }

    /**
    * @dataProvider SetUrlLinkData
    */
    public function testSetUrlLinks($testUrlLinks) {
        $person = new Person();

        $person->setUrlLinks($testUrlLinks);
        
        $this->AssertEquals($testUrlLinks, $person->getUrlLinks());
    }
    
    public function SetUrlLinkData() {
        return array(

            [['http://www.example.com', 'http://www.sfu.ca']],
            [['http://www.sfu.ca']]
        );
    }
    
}    