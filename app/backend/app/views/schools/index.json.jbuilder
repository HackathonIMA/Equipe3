json.array!(@schools) do |school|
  json.extract! school, :id, :ima_id, :name, :address
  json.url school_url(school, format: :json)
end
