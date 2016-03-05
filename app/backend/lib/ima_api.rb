# http://stackoverflow.com/a/748657/1574059

class Ima
  include HTTParty
  base_uri 'api.ima.sp.gov.br/v1'

  def initialize(client_id)
    @auth = {:username => u, :password => p}
  end

  # which can be :friends, :user or :public
  # options[:query] can be things like since, since_id, count, etc.
  def timeline(which=:friends, options={})
    options.merge!({:basic_auth => @auth})
    self.class.get("/statuses/#{which}_timeline.json", options)
  end

  def post(text)
    options = { :query => {:status => text}, :basic_auth => @auth }
    self.class.post('/statuses/update.json', options)
  end
end
