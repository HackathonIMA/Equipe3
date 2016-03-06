class User < ActiveRecord::Base
  has_many :shares
  has_many :interactions
end
