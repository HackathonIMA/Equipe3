json.array!(@interactions) do |interaction|
  json.extract! interaction, :id, :user, :share, :support
  json.url interaction_url(interaction, format: :json)
end
