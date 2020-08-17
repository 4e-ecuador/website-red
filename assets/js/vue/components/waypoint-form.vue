<template>
    <div>
<!--        <div class="row">-->
<!--            <div class="col-sm-3">-->
<!--                {{searchResultCount}}-->
<!--            </div>-->
<!--            <div class="col-2 btn-group">-->
<!--                <paginate-button-previous-->
<!--                    v-if="pagination['hydra:previous']"-->
<!--                    @paginate-previous="onPaginatePrevious"-->
<!--                    :link="pagination['hydra:previous']"-->
<!--                />-->
<!--            </div>-->
<!--            <div class="col-2 btn-group">-->
<!--                <paginate-button-next-->
<!--                    v-if="pagination['hydra:next']"-->
<!--                    @paginate-next="onPaginateNext"-->
<!--                    :link="pagination['hydra:next']"-->
<!--                />-->
<!--            </div>-->
<!--            <div class="col">-->
<!--                <search-bar @search="onSearchAgents"/>-->
<!--            </div>-->
<!--        </div>-->

        <waypoint-fields
            :item="item"
            :loading="loading"
         :type="this.type"/>
    </div>
</template>

<script>
import PaginateButtonNext from '@/vue/parts/paginate-button-next'
import PaginateButtonPrevious from '@/vue/parts/paginate-button-previous'
import SearchBar from '@/vue/parts/search-bar'
import {fetchItems} from '@/vue/services/waypoints-service'
import WaypointFields from '@/vue/components/waypoint-fields/index'
// import {translate,translatePlural} from '@/vue/services/translation-service'

export default {
    name: 'WaypointForm',
    components: {
        WaypointFields,
        PaginateButtonPrevious,
        PaginateButtonNext,
        SearchBar,
    },
    props: {
        type: {
            type: String,
            required: true,
        },
    },
    data: () => ({
        item: {},
        loading: false,
    }),
    created() {
        // this.loadItems()
    },
    computed: {
        searchResultCount() {
            return 'Found: '+this.totalItems
            // return translatePlural('search.result', this.totalItems)
            //     .replace('{count}', this.totalItems)
        },
    },
    methods: {
        onSearchAgents({term}) {
            this.searchTerm = term
            this.pageNum = 1
            this.loadItems()
        },
        onPaginateNext() {
            this.pageNum++
            this.loadItems()
        },
        onPaginatePrevious() {
            this.pageNum--
            this.loadItems()
        },
        async loadItems() {
            this.item = {
                id: 0
            }
            return
            this.loading = true
            let response
            try {
                response = await fetchItems(this.searchTerm, this.pageNum)
                this.loading = false
            } catch (e) {
                console.log(e)
                this.loading = false
                return
            }

            console.log(response)
            console.log(response.data)
            this.items = response.data['hydra:member']
            this.totalItems = response.data['hydra:totalItems']
            this.pagination = response.data['hydra:view']
            // TODO ugly maxpages...
            this.totalPages = this.pagination['hydra:last'] ? this.pagination['hydra:last'].replace(/^\D+/g, '') : 0
        },
    },
}
</script>
