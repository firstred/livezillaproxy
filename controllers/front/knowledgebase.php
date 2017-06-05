<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class LivezillaProxyKnowledgebaseModuleFrontController
 */
class LivezillaProxyKnowledgebaseModuleFrontController extends ModuleFrontController
{
    /**
     * Initialize content
     */
    public function initContent()
    {
        parent::initContent();

        if (isset($_GET['article'])) {
            $article = $this->retrieveArticleInfo($_GET['article']);
        } else {
            $article = false;
        }

        $this->context->smarty->assign(
            [
                'article' => $article,
            ]
        );

        $this->setTemplate('knowledgebase.tpl');
    }

    /**
     * @param string $id md5 article ID
     *
     * @return mixed
     */
    protected function retrieveArticleInfo($id)
    {
        $apiURL = Configuration::get(LivezillaProxy::API_LOCATION);

        // authentication parameters
        $postd['p_user'] = Configuration::get(LivezillaProxy::API_USER);
        $postd['p_pass'] = md5(Configuration::get(LivezillaProxy::API_PASSWORD));

        // function parameter
        $postd['p_knowledgebase_entries_list'] = 1;
        $postd['p_id'] = $id;
        $postd['p_json_pretty'] = 1;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiURL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postd));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);

        if ($server_output === false) {
            exit(curl_error($ch));
        }
        curl_close($ch);

        return json_decode($server_output);
    }
}
