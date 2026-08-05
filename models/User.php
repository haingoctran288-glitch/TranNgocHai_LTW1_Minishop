<?php
class User {
    public ?int $id = null;
    public string $fullname;
    public string $username;
    public string $password;
    public string $email;
    public string $phone;
    public string $address;
    public int $role = 0;
    public int $status = 1;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;

    public function __construct(string $fullname = "", string $username = "", string $password = "", string $email = "", string $phone = "", string $address = "", int $role = 0, int $status = 1) {
        $this->fullname = $fullname;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->role = $role;
        $this->status = $status;
    }
}
?>
