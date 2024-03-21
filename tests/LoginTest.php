<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private $dbMock;

    // Set Up DB
    protected function setUp(): void
    {
        parent::setUp();
        $this->dbMock = $this->createMock(PDO::class);
        $_SESSION = [];
    }

    // Test Successful login 
    public function testSuccessfulLogin()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('rowCount')->willReturn(1);
        $stmtMock->method('fetch')->willReturn(['Password' => password_hash('validPassword', PASSWORD_DEFAULT)]);

        $this->dbMock->method('prepare')->willReturn($stmtMock);

        global $db;
        $db = $this->dbMock;
        $_POST = [
            'login-username' => 'sd',
            'login-password' => 'sd',
            'userType' => 'customer'
        ];

        ob_start();
        include __DIR__ . '/../php/loggingIn.php';
        ob_end_clean();

        $this->assertArrayHasKey('username', $_SESSION, "The 'username' key should be set in the session.");
        $this->assertEquals('sd', $_SESSION['username'], "The session username should match the POST data.");
    }

    //Test Incorrect Password
    public function testIncorrectPassword()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('rowCount')->willReturn(1);
        $stmtMock->method('fetch')->willReturn(['Password' => password_hash('validPassword', PASSWORD_DEFAULT)]);
        $this->dbMock->method('prepare')->willReturn($stmtMock);
        global $db;
        $db = $this->dbMock;

        $_POST = [
            'login-username' => 'sd',
            'login-password' => 'incorrectPassword',
            'userType' => 'customer'
        ];

        ob_start();
        include __DIR__ . '/../php/loggingIn.php';
        ob_end_clean();

        $this->assertArrayHasKey('error_message', $_SESSION, "Session should have an error_message key.");
        $this->assertEquals('Incorrect Password.', $_SESSION['error_message'], "The error message for an incorrect password does not match expected.");
    }

    //Clean Session
    protected function tearDown(): void
    {
        $_SESSION = [];
        parent::tearDown();
    }


    // Test non-existent username
    public function testLoginWithNonexistentUsername()
    {
        //Mock PDOStatement
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('rowCount')->willReturn(0);
        $stmtMock->method('fetch')->willReturn(false);

        $this->dbMock->method('prepare')->willReturn($stmtMock);

        global $db;
        $db = $this->dbMock;
        $_POST = [
            'login-username' => 'nonexistentUser',
            'login-password' => 'anyPassword',
            'userType' => 'customer'
        ];

        ob_start();
        include __DIR__ . '/../php/loggingIn.php';
        ob_end_clean();

        $this->assertArrayHasKey('error_message', $_SESSION);
        $this->assertEquals('Username does not exist.', $_SESSION['error_message']);
    }

    //Test Signup Validations
    public function testSignupDataValidation() {
        include __DIR__ . '/../php/validateSignup.php';

        //Test data
        $fName = 'John';
        $lName = 'Doe';
        $email = 'john@example.com';
        $pNumber = '07234356789';
        $homeAddress = "52 Mont Street";
        $postcode = "B56 3GF";
        $username = 'johndoe';
        $validPassword = 'Valid@Password123';
        $invalidPassword = 'weak';

        //Call the function with valid data
        $validErrors = validateSignupData($fName, $lName, $email, $pNumber, $homeAddress, $postcode, $username, $validPassword);

        $this->assertEmpty($validErrors);

        $invalidErrors = validateSignupData('', '', 'invalidemail', 'notanumber', "", "invalidpostcode", '', $invalidPassword);

        var_dump($invalidErrors);
        $this->assertNotEmpty($invalidErrors);
    }
}
?>