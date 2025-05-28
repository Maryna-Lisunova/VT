namespace App\Models;

class User {
    public ?int $id;
    public string $username;
    public string $email;
    public string $passwordHash;

    public function __construct(?int $id, string $username, string $email, string $passwordHash) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }
}

