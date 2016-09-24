<?php
namespace ByTorsten\Neos\ExtendUi\ViewHelpers;

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\Flow\Resource\ResourceManager;
use TYPO3\Flow\Annotations as Flow;

class MappingViewHelper extends AbstractViewHelper {

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * @Flow\InjectConfiguration("replacements")
     * @var array
     */
    protected $replacements;

    /**
     * @Flow\Inject
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * @return string
     */
    public function render()
    {
        $mapping = [
            '*' => []
        ];

        foreach($this->replacements as $path => $replacement) {
            preg_match('/\/([^\/]+)\/?$/', $path, $matches);
            $className = $matches[1];
            $replacementPath = $this->resourceManager->getPublicPackageResourceUriByPath($replacement);
            $mapping['*'][$path] = $replacementPath;
            $mapping[$replacementPath] = [
                $className => $path
            ];
        }

        return json_encode($mapping, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}