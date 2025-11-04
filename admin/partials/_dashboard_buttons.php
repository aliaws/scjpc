<div class="my-2 float-end" role="group" aria-label="Basic example">
    <button
        id="clear-export"
        type="button"
        class="btn btn-primary clear-cache"
        data-api-action="clear-cdn-cache"
        data-key="/exports/*"
        title="Clear Export Cache">
        <i class="bi bi-x-circle"></i> Export Cache
    </button>

    <button
        id="clear-pdf"
        type="button"
        class="btn btn-primary clear-cache"
        data-api-action="clear-cdn-cache"
        data-key="/pdf/*"
        title="Clear PDF Cache">
        <i class="bi bi-x-circle"></i> PDF Cache
    </button>

    <button
        id="clear-redis"
        type="button"
        class="btn btn-primary clear-cache"
        data-api-action="flush-redis-cache"
        title="Clear Redis Cache">
        <i class="bi bi-x-circle"></i> Redis Cache
    </button>

    <button
        id="re-index"
        type="button"
        class="btn btn-primary clear-cache"
        data-method="POST"
        data-api-action="elastic-search-re-index"
        data-post-key="elastic_search_re_index"
        data-elastic_search_re_index="yes"
        title="Re-Index Elastic Search">
        <i class="bi bi-arrow-repeat"></i> Elastic Search
    </button>

    <button
        id="remove-deleted-data"
        type="button"
        class="btn btn-primary clear-cache"
        data-method="POST"
        data-api-action="remove-deleted-data"
        data-post-key="remove_deleted_data"
        data-remove_deleted_data="yes"
        title="Remove Deleted Data">
        <i class="bi bi-trash"></i> Deleted Data
    </button>

    <button
        id="clean-es-orphans"
        type="button"
        class="btn btn-primary clear-cache"
        data-method="POST"
        data-api-action="remove-es-orphans"
        data-post-key="remove_es_orphans"
        data-remove_es_orphans="yes"
        title="Remove ES records not found in DB">
        <i class="bi bi-database-x"></i> ES Orphaned Records
    </button>
    <button
            id="index-es-nodata"
            type="button"
            class="btn btn-primary clear-cache"
            data-method="POST"
            data-api-action="index-es-nodata"
            data-post-key="index_es_nodata"
            data-index_es_nodata="yes"
            title="Index this  ES records after Migration in DB">
        <i class="bi bi-database-x"></i> ES No Index Data
    </button>
</div>