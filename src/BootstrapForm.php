<?php declare(strict_types = 1);

namespace Contributte\FormsBootstrap;

use Contributte\FormsBootstrap\Traits\AddRowTrait;
use Contributte\FormsBootstrap\Traits\BootstrapContainerTrait;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IContainer;
use Nette\Forms\IFormRenderer;
use Nette\InvalidArgumentException;
use Nette\Utils\Html;

/**
 * Class BootstrapForm
 * Form rendered using Bootstrap 4
 *
 * @property bool $ajax
 * @property int  $renderMode
 * @property bool $showValidation     If valid fields should explicitly be green if valid
 * @property bool $autoShowValidation If true, valid inputs will be explicitly green on unsuccessful submit
 */
class BootstrapForm extends Form
{

	use BootstrapContainerTrait;
	use AddRowTrait;

	/** @var string Class to be added if this is ajax. Defaults to 'ajax' */
	public $ajaxClass = 'ajax';

	/** @var Html */
	protected $elementPrototype;

	/** @var bool */
	private $isAjax = true;

	/** @var bool */
	private $showValidation = false;

	/** @var bool */
	private $autoShowValidation = true;

	/**
	 * @param int|IContainer|null $container
	 */
	public function __construct($container = null)
	{
		parent::__construct($container);
		$this->setRenderer(new BootstrapRenderer());

		$prototype = Html::el('form', [
			'action' => '',
			'method' => self::POST,
			'class'  => [],
		]);
		$this->elementPrototype = $prototype;

		/**
		 * @param BootstrapForm $form
		 */
		$this->onError[] = function ($form): void {
			$form->showValidation = $this->autoShowValidation;
		};
	}

	public function getElementPrototype(): Html
	{
		return $this->elementPrototype;
	}

	/**
	 * @return BootstrapRenderer
	 */
	public function getRenderer(): IFormRenderer
	{
		return parent::getRenderer();
	}

	/**
	 * @return static
	 */
	public function setRenderer(?IFormRenderer $renderer = null)
	{
		if (!$renderer instanceof BootstrapRenderer) {
			throw new InvalidArgumentException('Must be a BootstrapRenderer');
		}

		parent::setRenderer($renderer);

		return $this;
	}

	public function getRenderMode(): int
	{
		return $this->getRenderer()->getMode();
	}

	/**
	 * @return bool if form is ajax. True by default.
	 */
	public function isAjax(): bool
	{
		return $this->isAjax;
	}

	public function isAutoShowValidation(): bool
	{
		return $this->autoShowValidation;
	}

	public function setAutoShowValidation(bool $autoShowValidation): BootstrapForm
	{
		$this->autoShowValidation = $autoShowValidation;

		return $this;
	}

	/**
	 * If valid fields should explicitly be green
	 */
	public function isShowValidation(): bool
	{
		return $this->showValidation;
	}

	/**
	 * If valid fields should explicitly be green
	 *
	 * @return static
	 */
	public function setShowValidation(bool $showValidation)
	{
		$this->showValidation = $showValidation;

		return $this;
	}

	/**
	 * @return static
	 */
	public function setAjax(bool $isAjax = true)
	{
		$this->isAjax = $isAjax;

		BootstrapUtils::standardizeClass($this->getElementPrototype());
		$prototypeClass = $this->getElementPrototype()->class;

		$present = in_array($this->ajaxClass, $prototypeClass);
		if ($present && !$isAjax) {
			// remove the class
			$prototypeClass = array_diff($prototypeClass, [$this->ajaxClass]);
		} elseif (!$present && $isAjax) {
			// add class
			$prototypeClass[] = $this->ajaxClass;
		}

		$this->getElementPrototype()->class = $prototypeClass;

		return $this;
	}

	/**
	 * @return static
	 */
	public function setRenderMode(int $renderMode)
	{
		$this->getRenderer()->setMode($renderMode);

		return $this;
	}

}
