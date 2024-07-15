<?php
declare(strict_types=1);

namespace Flownative\WorkspacePreview\Fusion;

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Exception as DomainException;
use Neos\Neos\Domain\Service\UserInterfaceModeService;
use Neos\Neos\Fusion\ConvertUrisImplementation as OriginalImplementation;

class ConvertUrisImplementation extends OriginalImplementation
{

    /**
     * @Flow\Inject
     * @var UserInterfaceModeService
     */
    protected $interfaceRenderModeService;

    /**
     * @return string
     * @throws DomainException
     */
    public function evaluate(): string
    {
        $currentRenderingMode = $this->interfaceRenderModeService->findModeByCurrentUser();
        $forceConversionPathPart = 'forceConversion';
        $isPersonalWorkspace = $this->fusionValue('node')->getContext()->getWorkspace()->isPersonalWorkspace();

        //Neos 8 can't differentiate between the content editing view and the frontend preview in internal workspaces, but
        // the frontend preview uses the baseWorkspace instead of the personalWorkspace, so we use that to check where we are
        if ($currentRenderingMode->isEdit() === false || ($currentRenderingMode->isEdit() === true && $isPersonalWorkspace === false)) {
            $fullPath = $this->path . '/' . $forceConversionPathPart;
            $this->fusionValueCache[$fullPath] = true;
        }

        return parent::evaluate();
    }

}
