<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=hupd for example request/response

class noridEppUpdateHostRequest extends eppUpdateHostRequest {

    use noridEppHostRequestTrait;

    function __construct($objectname, $addInfo = null, $removeInfo = null, $updateInfo = null, $namespacesinroot = true) {
        parent::__construct($objectname, $addInfo, $removeInfo, $updateInfo, $namespacesinroot);

        if (($addInfo instanceof noridEppHost) || ($removeInfo instanceof noridEppHost)) {
            $this->updateExtHost($hostname, $addInfo, $removeInfo);
        } else {
            throw new eppException('addInfo, removeInfo and updateInfo need to be noridEppHost objects');
        }

        $this->addSessionId();
    }

    public function updateExtHost($hostname, $addInfo, $removeInfo) {
        if ($addInfo instanceof noridEppHost) {
            // Add Norid EPP extensions
            if (!is_null($addInfo->getExtContact()) || !is_null($addInfo->getExtSponsoringClientID())) {
                $extaddcmd = $this->createElement('no-ext-host:add');
                $this->addHostExtChanges($extaddcmd, $addInfo);
                $this->getHostExtension()->appendChild($extaddcmd);
            }
        }
        if ($removeInfo instanceof noridEppHost) {
            // Add Norid EPP extensions
            if (!is_null($removeInfo->getExtContact()) || !is_null($removeInfo->getExtSponsoringClientID())) {
                $extremcmd = $this->createElement('no-ext-host:rem');
                $this->addHostExtChanges($extremcmd, $removeInfo);
                $this->getHostExtension()->appendChild($extremcmd);
            }
        }
    }

    private function addHostExtChanges(\DOMElement $element, noridEppHost $host) {
        if (!is_null($host->getExtContact())) {
            $element->appendChild($this->createElement('no-ext-host:contact', $host->getExtContact()));
        }
    }

}