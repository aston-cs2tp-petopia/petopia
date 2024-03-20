<?php
require_once '../vendor/autoload.php';

class LoginTest extends \PHPUnit\Framework\TestCase {
    
    // Mock database connection
    private $dbMock;

    // Set up before each test method
    protected function setUp(): void {
        // Create a mock for the database connection
        $this->dbMock = $this->getMockBuilder('PDO')
                             ->disableOriginalConstructor()
                             ->getMock();
    }

    // Test successful login
    public function testSuccessfulLogin() {
        // Set up mock behavior for successful login
        $this->dbMock->expects($this->once())
                     ->method('prepare')
                     ->willReturn($this->getMockBuilder('PDOStatement')
                                       ->getMock());
        $this->dbMock->expects($this->once())
                     ->method('execute')
                     ->willReturn(true);
        $this->dbMock->expects($this->once())
                     ->method('rowCount')
                     ->willReturn(1);
        $this->dbMock->expects($this->once())
                     ->method('fetch')
                     ->willReturn(array('Password' => password_hash('correct_password', PASSWORD_DEFAULT)));

        // Simulate form submission data
        $_POST['login-username'] = 'test_user';
        $_POST['login-password'] = 'correct_password';

        // Call the login script function
        include '../login.php';

        // Instead of xdebug_get_headers(), use this function to capture headers
function captureHeaders() {
    $headers = [];
    foreach (headers_list() as $header) {
        $parts = explode(':', $header, 2);
        if (count($parts) === 2) {
            $headers[trim($parts[0])] = trim($parts[1]);
        }
    }
    return $headers;
}

// Within the test method
$headers = captureHeaders();


        // Assert that the user is redirected after successful login
        if (!empty($headers)) {
            $this->assertStringContainsString('Location: ./index.php', $headers[0]);
        } else {
            $this->fail('No headers found.');
        }
    }

    // Test incorrect password
    public function testIncorrectPassword() {
        // Set up mock behavior for incorrect password
        $this->dbMock->expects($this->once())
                     ->method('prepare')
                     ->willReturn($this->getMockBuilder('PDOStatement')
                                       ->getMock());
        $this->dbMock->expects($this->once())
                     ->method('execute')
                     ->willReturn(true);
        $this->dbMock->expects($this->once())
                     ->method('rowCount')
                     ->willReturn(1);
        $this->dbMock->expects($this->once())
                     ->method('fetch')
                     ->willReturn(array('Password' => password_hash('correct_password', PASSWORD_DEFAULT)));

        // Simulate form submission data with incorrect password
        $_POST['login-username'] = 'test_user';
        $_POST['login-password'] = 'incorrect_password';

        // Call the login script function
        include '../login.php';

        // Assert that an error message is set
        $this->assertEquals('Incorrect Password.', $_SESSION['error_message']);
    }

    // Test signup data validation
    public function testSignupDataValidation() {
        // Include the function to be tested
        include '../php/validateSignup.php';

        // Test data
        $fName = 'John';
        $lName = 'Doe';
        $email = 'john@example.com';
        $pNumber = '0123456789';
        $username = 'johndoe';
        $validPassword = 'Valid@Password123';
        $invalidPassword = 'weak';

        // Call the function with valid data
        $validErrors = validateSignupData($fName, $lName, $email, $pNumber, $username, $validPassword);

        // Assert that no errors are returned for valid data
        $this->assertEmpty($validErrors);

        // Call the function with invalid data
        $invalidErrors = validateSignupData('', '', 'invalidemail', 'notanumber', '', $invalidPassword);

        // Assert that errors are returned for invalid data
        var_dump($invalidErrors); // Debugging output
        $this->assertNotEmpty($invalidErrors);
        // Adjust assertions based on the actual content of $invalidErrors
    }
}
?>
