<?php
class Customer {
    public ?int $id = null;
    public string $fullname;
    public string $phone;
    public ?string $email = null;
    public ?string $address = null;
    public ?string $note = null;

    public function __construct(string $fullname = "", string $phone = "", ?string $email = null, ?string $address = null, ?string $note = null) {
        $this->fullname = $fullname;
        $this->phone = $phone;
        $this->email = $email;
        $this->address = $address;
        $this->note = $note;
    }
}
?>
