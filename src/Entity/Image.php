<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\UplaodImageAction;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *     attributes={
 *          "formats"={"json","form"={"multipart/form-data"}},
 *          "order"= {"id": "DESC"}
 *     },
 *     collectionOperations={
 *       "get",
 *       "post"={
 *          "method"="POST",
 *          "path"="/images",
 *          "controller"= UplaodImageAction::class,
 *          "defaults"={"_api_receive"=false},
 *          "swagger_context" = {
    *          "consumes" = {
    *                "multipart/form-data",
    *            },
    *            "parameters" = {
    *                 {
    *                      "name" = "file",
    *                      "in" = "formData",
    *                      "required" = "true",
    *                      "type" = "file",
    *                      "description" = "The file to upload"
    *                 }
    *             }
 *              }
 *        }
 *    }
 * )
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @Vich\Uploadable()
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"post","get-post-with-author"})
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="url")
     * @Assert\NotNull()
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"post","get-post-with-author"})
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $alt;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }
    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;
    }
}
