<?php
class Order {
    public ?int $id = null;
    public int $customerId;
    public ?int $userId = null;
    public string $orderCode;
    public float $totalAmount = 0;
    public ?string $note = null;
    public int $status = 0;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;
    public ?string $customerName = null;
    public ?string $userName = null;

    public function __construct(int $customerId = 0, ?int $userId = null, string $orderCode = "", float $totalAmount = 0, ?string $note = null, int $status = 0) {
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->orderCode = $orderCode;
        $this->totalAmount = $totalAmount;
        $this->note = $note;
        $this->status = $status;
    }
}
?>
