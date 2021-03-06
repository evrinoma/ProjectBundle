<?php


namespace Evrinoma\ProjectBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use Evrinoma\GridBundle\AgGrid\ColumnDef;
use Evrinoma\ProjectBundle\Model\AbstractBaseProject;
use Evrinoma\UtilsBundle\Manager\AbstractEntityManager;
use Evrinoma\UtilsBundle\Rest\RestTrait;

/**
 * Class ProjectManager
 *
 * @package Evrinoma\ProjectBundle\Manager
 */
class ProjectManager extends AbstractEntityManager
{
    use RestTrait;

//region SECTION: Fields
    /**
     * @var string
     */
    protected $repositoryClass = AbstractBaseProject::class;
//endregion Fields

//region SECTION: Constructor
    /**
     * ProjectManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param string                 $repositoryClass
     */
    public function __construct(EntityManagerInterface $entityManager, string $repositoryClass)
    {
        $this->repositoryClass = $repositoryClass;
        parent::__construct($entityManager);
    }

//endregion Constructor

//region SECTION: Getters/Setters
    /**
     * @param $projectDto
     *
     * @return $this
     */
    public function get($projectDto): self
    {
        if ($projectDto) {
            $this->setData($this->repository->setDto($projectDto)->findProject());
        } else {
            $this->setRestBadRequest();
        }

        return $this;
    }

    public function getAll()
    {
        return $this->getRepositoryAll($this->repositoryClass)->getData();
    }

    public function getColumnDefs()
    {
        $id = new ColumnDef();
        $id->setType(ColumnDef::NUMBER_COLUMN)->setHeaderName('ID')->setField('id')->setWidth(1)->setEditable()->setResizable();

        $name = new ColumnDef();
        $name->setHeaderName('Название')->setField('name')->setWidth(200);

        $dateStart = new ColumnDef();
        $dateStart->setType(ColumnDef::DATE_COLUMN)->setHeaderName('Дата начала')->setField('dateStart')->setWidth(5)->setResizable()
            ->setCellEditor(ColumnDef::CELL_EDITOR_DATE_PICKER);

        $dateFinish = new ColumnDef();
        $dateFinish->setType(ColumnDef::DATE_COLUMN)->setHeaderName('Дата окончания')->setField('dateFinish')->setWidth(5)->setResizable()
            ->setCellEditor(ColumnDef::CELL_EDITOR_DATE_PICKER);

        $description = new ColumnDef();
        $description->setHeaderName('Описание')->setField('description')->setWidth(140)->setCellEditor(ColumnDef::CELL_EDITOR_AG_LARGE_TEXT_CELL_EDITOR);

        return [$id, $name, $dateStart, $dateFinish, $description];
    }

    public function getRestStatus(): int
    {
        return $this->status;
    }
//endregion Getters/Setters
}