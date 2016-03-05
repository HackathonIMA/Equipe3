# http://stackoverflow.com/a/748657/1574059

class ImaApi
  include HTTParty

  format :json
  base_uri 'api.ima.sp.gov.br/v1'

  def initialize(client_id)
    @client_id = client_id
  end

  def escolas(offset = 0, limit = 50)
    self.class.get('/educacao', query: { offset: offset, limit: 20 },
                                headers: { "client_id" => @client_id })
  end

  def escola(id)
    self.class.get("/educacao/#{id}", headers: { "client_id" => @client_id })
  end

  # def timeline(which=:friends, options={})
  #   options.merge!({:basic_auth => @auth})
  #   self.class.get("/statuses/#{which}_timeline.json", options)
  # end
end
