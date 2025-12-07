<script setup lang="ts">
import PlotlyRenderer from '@vue-pivottable/plotly-renderer';
import { markRaw } from 'vue';
import { PivotUtilities, Renderer, VuePivottableUi } from 'vue-pivottable';
import 'vue-pivottable/dist/vue-pivottable.css';

const props = defineProps(['items']);

const usFmtInt = PivotUtilities.numberFormat({ digitsAfterDecimal: 0 });
const aggregators = ((tpl) => ({
    Count: tpl.count(usFmtInt),
}))(PivotUtilities.aggregatorTemplates);

// add plotly renderer to default renderer
const renderers = markRaw({
    ...Renderer,
    ...PlotlyRenderer,
});
</script>

<template>
    <VuePivottableUi
        :data="props.items"
        :renderers="renderers"
        renderer-name="Table"
        aggregatorName="Count"
        :aggregators="aggregators"
        :hiddenFromAggregators="['Dot Chart']"
        :showRowTotal="true"
        :showColTotal="true"
    >
        <template #aggregatorCell> <i class="fas fa-calculator" style="margin-right: 0.25rem"></i> </template>
    </VuePivottableUi>
</template>
<style scoped>
.pvtRenderers {
    width: 18% !important;
}
</style>
