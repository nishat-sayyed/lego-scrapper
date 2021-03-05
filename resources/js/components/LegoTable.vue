<template>
  <div class="overflow-x-auto">
    <div
      class="min-w-screen min-h-screen flex items-center justify-center bg-gray-100 font-sans overflow-hidden"
    >
      <div class="w-full lg:w-11/12">
        <div class="bg-white shadow-md rounded my-6">
          <table class="min-w-max w-full table-auto">
            <thead>
              <tr
                class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal"
              >
                <th class="py-3 px-6 text-left">Item #</th>
                <th class="py-3 px-6 text-left">Name</th>
                <th class="py-3 px-6 text-center">Market</th>
                <th class="py-3 px-6 text-center">Price</th>
                <th class="py-3 px-6 text-center">Sale Price</th>
                <th class="py-3 px-6 text-center">Discount amount</th>
                <th class="py-3 px-6 text-center">Discount %</th>
                <th class="py-3 px-6 text-center">Status</th>
                <th class="py-3 px-6 text-center">Date spotted</th>
              </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
              <tr
                v-for="lego in legos"
                :key="lego.id"
                class="border-b border-gray-200 hover:bg-gray-100"
              >
                <td class="py-3 px-6 text-left whitespace-nowrap">
                  <div class="flex items-center text-purple-600">
                    <a target="_blank" :href="`https://${lego.url}`">{{
                      lego.number
                    }}</a>
                  </div>
                </td>
                <td class="py-3 px-6 text-left">
                  <span class="font-medium">
                    <a target="_blank" :href="`https://${lego.url}`"
                      >{{ lego.name.substring(0, 40) }}
                    </a>
                  </span>
                </td>
                <td class="py-3 px-6 text-center">
                  <span>{{ lego.marketplace.toUpperCase() }}</span>
                </td>
                <td class="py-3 px-6 text-center">
                  <span class="font-medium">{{
                    getCurrencySymbol(lego.marketplace) + lego.price
                  }}</span>
                </td>
                <td class="py-3 px-6 text-center">
                  <span class="font-medium">{{
                    getCurrencySymbol(lego.marketplace) +
                    (lego.sale_price ? lego.sale_price : lego.price)
                  }}</span>
                </td>
                <td class="py-3 px-6 text-center">
                  <span>{{
                    getCurrencySymbol(lego.marketplace) + lego.discount_amount
                  }}</span>
                </td>
                <td class="py-3 px-6 text-center">
                  <span>{{ lego.discount_percentage }}%</span>
                </td>
                <td class="py-3 px-6 text-center">
                  <span
                    :class="[
                      lego.stock_status === 'Available'
                        ? 'bg-green-200 text-green-600'
                        : 'bg-red-200 text-red-600',
                      'py-1 px-3 rounded-full text-xs',
                    ]"
                    >{{ lego.stock_status }}</span
                  >
                </td>
                <td class="py-3 text-center">
                  <span>{{ lego.date_spotted }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      legos: [],
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      fetch("/api/legos")
        .then((response) => response.json())
        .then((response) => (this.legos = response));
    },
    getCurrencySymbol(market) {
      return market === "US" ? "$" : "£";
    },
  },
};
</script>
