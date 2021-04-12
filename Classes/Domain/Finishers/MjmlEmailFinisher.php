<?php
namespace Saccas\Mjml\Domain\Finishers;

use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Form\Domain\Finishers\Exception\FinisherException;
use TYPO3\CMS\Form\Domain\Runtime\FormRuntime;
use TYPO3\CMS\Form\ViewHelpers\RenderRenderableViewHelper;
use Saccas\Mjml\View\MjmlBasedView;

class MjmlEmailFinisher extends \TYPO3\CMS\Form\Domain\Finishers\EmailFinisher
{
    /**
     * @param FormRuntime $formRuntime
     * @param string $format
     * @return StandaloneView
     * @throws FinisherException
     */
    protected function initializeStandaloneView(FormRuntime $formRuntime, string $format = 'Html'): StandaloneView
    {
        $standaloneView = $this->objectManager->get(MjmlBasedView::class);

        if (isset($this->options['templatePathAndFilename'])) {
            $this->options['templatePathAndFilename'] = strtr($this->options['templatePathAndFilename'], [
                '{@format}' => $format
            ]);
            $standaloneView->setTemplatePathAndFilename($this->options['templatePathAndFilename']);
        } else {
            if (!isset($this->options['templateName'])) {
                throw new FinisherException('The option "templateName" must be set for the EmailFinisher.', 1327058829);
            }
            // Use local variable instead of augmenting the options to
            // keep the format intact when sending multi-format mails
            $templateName = strtr($this->options['templateName'], [
                '{@format}' => $format
            ]);
            $standaloneView->setTemplate($templateName);
        }

        $standaloneView->assign('finisherVariableProvider', $this->finisherContext->getFinisherVariableProvider());

        if (isset($this->options['templateRootPaths']) && is_array($this->options['templateRootPaths'])) {
            $standaloneView->setTemplateRootPaths($this->options['templateRootPaths']);
        }

        if (isset($this->options['partialRootPaths']) && is_array($this->options['partialRootPaths'])) {
            $standaloneView->setPartialRootPaths($this->options['partialRootPaths']);
        }

        if (isset($this->options['layoutRootPaths']) && is_array($this->options['layoutRootPaths'])) {
            $standaloneView->setLayoutRootPaths($this->options['layoutRootPaths']);
        }

        if (isset($this->options['variables'])) {
            $standaloneView->assignMultiple($this->options['variables']);
        }

        $standaloneView->assign('form', $formRuntime);
        $standaloneView->getRenderingContext()
            ->getViewHelperVariableContainer()
            ->addOrUpdate(RenderRenderableViewHelper::class, 'formRuntime', $formRuntime);

        return $standaloneView;
    }
}
