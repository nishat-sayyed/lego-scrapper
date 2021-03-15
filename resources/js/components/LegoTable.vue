<template>
  <div class="overflow-x-auto">
    <div v-if="!loading" class="flex flex-col pl-3">
      <Searchbar styles="mb-2" v-model="searchTerm" />
      <div class="flex flex-row">
        <SearchFilter
          styles="mr-4"
          title="Marketplace"
          :options="[
            { label: 'All', value: 'All' },
            { label: 'US', value: 'US' },
            { label: 'UK', value: 'UK' },
          ]"
          v-model="marketplace"
        />
        <SearchFilter
          title="Stock status"
          :options="[
            { label: 'All', value: 'All' },
            { label: 'Available', value: 'Available' },
            { label: 'Out of stock', value: 'Out of stock' },
          ]"
          v-model="status"
        />
      </div>
      <div class="font-semibold">
        <span>
          Found {{ resultSet.length }}
          {{ `${resultSet.length === 1 ? "result" : "results"}` }}
        </span>
      </div>
    </div>
    <div
      class="min-w-screen min-h-screen flex justify-center bg-gray-100 font-sans overflow-hidden"
    >
      <div class="w-full">
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
                <!-- <th class="py-3 px-6 text-center">Watchlist</th> -->
              </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
              <tr
                v-for="lego in resultSet"
                :key="lego.id"
                class="border-b border-gray-200 hover:bg-gray-100"
              >
                <td class="py-3 px-6 text-left whitespace-nowrap">
                  <div class="flex items-center text-purple-600">
                    <a
                      v-html="highlightMatches(lego.number)"
                      target="_blank"
                      :href="`https://${lego.url}`"
                    ></a>
                  </div>
                </td>
                <td class="py-3 px-6 text-left">
                  <span class="font-medium">
                    <a
                      v-html="highlightMatches(lego.name)"
                      target="_blank"
                      :href="`https://${lego.url}`"
                    >
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
                    getCurrencySymbol(lego.marketplace) + lego.sale_price
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
              <tr v-if="loading">
                <div class="flex justify-center py-4">
                  <span class="font-medium">Loading data...</span>
                </div>
              </tr>
              <tr v-else-if="resultSet.length === 0">
                <div class="flex justify-center py-4">
                  <span class="font-medium">No results found</span>
                </div>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Searchbar from "./Searchbar.vue";
import SearchFilter from "./SearchFilter.vue";

export default {
  data() {
    return {
      legos: [],
      searchTerm: "",
      loading: false,
      marketplace: "All",
      status: "All",
    };
  },
  computed: {
    resultSet() {
      let legos = this.legos.filter((row) => {
        const name = row.name.toString().toLowerCase();
        const number = row.number.toString().toLowerCase();
        const searchTerm = this.searchTerm.toLowerCase();
        return name.includes(searchTerm) || number.includes(searchTerm);
      });
      if (this.marketplace !== "All") {
        legos = legos.filter((row) =>
          this.marketplace.includes(row.marketplace)
        );
      }
      if (this.status !== "All") {
        legos = legos.filter((row) =>
          this.status.toLowerCase().includes(row.stock_status.toLowerCase())
        );
      }

      return legos;
    },
  },
  mounted() {
    this.fetchData();
  },
  components: { Searchbar, SearchFilter },
  methods: {
    fetchData() {
      this.loading = true;
      fetch("/api/legos")
        .then((response) => response.json())
        .then((response) => {
          this.legos = response;
          this.loading = false;
        });
    },
    getCurrencySymbol(market) {
      return market === "US" ? "$" : "£";
    },
    highlightMatches(text) {
      const matchExists = text
        .toString()
        .toLowerCase()
        .includes(this.searchTerm.toLowerCase());
      if (!matchExists) return text;

      const re = new RegExp(this.searchTerm, "ig");
      return text
        .toString()
        .replace(
          re,
          (matchedText) =>
            `<strong class="text-yellow-600 bg-yellow-200">${matchedText}</strong>`
        );
    },
  },
};
</script>
