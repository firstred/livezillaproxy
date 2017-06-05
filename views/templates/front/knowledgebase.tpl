{if isset($article) && isset($article->KnowledgeBaseEntries[0]->KnowledgeBaseEntry->Title) && isset($article->KnowledgeBaseEntries[0]->KnowledgeBaseEntry->Value)}
    <h1>{$article->KnowledgeBaseEntries[0]->KnowledgeBaseEntry->Title}</h1>
    {$article->KnowledgeBaseEntries[0]->KnowledgeBaseEntry->Value}
{else}
    <p>Article not found</p>
{/if}
