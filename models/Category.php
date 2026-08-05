<?php
class Category
{
    public ?int $id = null;
    public string $name;
    public string $slug;
    public ?string $image = null;
    public ?string $description = null;
    public int $status = 1;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;

    public function __construct(string $name = "", string $slug = "", ?string $image = null, ?string $description = null, int $status = 1)
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->image = $image;
        $this->description = $description;
        $this->status = $status;
    }
}
?>