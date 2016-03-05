# http://stackoverflow.com/a/748657/1574059

class ImaApi
  include HTTParty

  format :json
  base_uri 'api.ima.sp.gov.br/v1'

  def initialize(client_id)
    @client_id = client_id
  end

  def escolas(offset = 0, limit = 50, filters = {}, fields = {})
    self.class.get('/educacao', query: { offset: offset, limit: limit, filters: parse_filters(filters),  fields: parse_fields(fields) },
                                headers: { "client_id" => @client_id }).parsed_response
  end

  def escola(id)
    self.class.get("/educacao/#{id}", headers: { "client_id" => @client_id }).parsed_response
  end

  private

  def parse_filters(filters = {})
    filters.keys.inject('') {|old, key| old += "#{key.to_s}:#{filters[key]}"}
  end

  def parse_fields(fields = {})
    fields.inject('') {|old, add| old += "#{add},"}.chomp(',')
  end

  # def timeline(which=:friends, options={})
  #   options.merge!({:basic_auth => @auth})
  #   self.class.get("/statuses/#{which}_timeline.json", options)
  # end
end
