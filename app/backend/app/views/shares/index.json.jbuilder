json.array!(@shares) do |share|
  json.extract! share, :id, :title, :description, :category, :school_id, :date, :icon
  json.url share_url(share, format: :json)
end
