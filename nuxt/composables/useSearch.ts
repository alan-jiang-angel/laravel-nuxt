export const useSearch = (src, searchText) => {
  const regex = new RegExp(searchText, "gi");
  // "i" flag for case-insensitive search, "g" flag for global search
  return src.replace(regex, `<b class="bg-primary text-white">${searchText}</b>`);
}
